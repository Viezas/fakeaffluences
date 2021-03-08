<?php

namespace App\Http\Controllers;

use App\Mail\Res_Can_Mail;
use Config\information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;


class ApiController extends Controller
{
    public function infos()
    {
        return response()->json(information::establishmentInfos(), 200);
    }

    public function booking(Request $request)
    {
        $rules = array(
            'email' => 'required|email',
            'date' => 'required|date_format:"Y-m-d"',
            'time' => 'required',
            'cgu' => 'accepted'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $rules = information::reservationRulls();
        $establishmentInfos = information::establishmentInfos();

        if(date('H').':00' >= ($rules['max_hours'] - $rules['reservation_duration']).":00" && date('Y-m-d') >= $request['date']){
            return response()->json(['error' => "L'heure et/ou la date de réservation de votre créneau est invalid !"], 400);        
        }

        if(in_array(date("D", strtotime($request["date"])), $rules['dayClose'])){
            return response()->json(['error' => "Le jour de réservation ne figure pas parmis les jours disponibles !"], 400);
        }

        if(!(strlen($request["time"]) == 5) || !str_contains($request["time"], ':00')){
            return response()->json(['error' => "Le créneau horaire doit etre au format suivant : hh:00 !"], 400);

        }

        if($request["time"] > ($rules['max_hours'] - $rules['reservation_duration']).":00"){
            return response()->json(['error' => "L'établissement : {$establishmentInfos['name']} étant ouvert de {$rules['min_hours']}h à {$rules['max_hours']}h, vous êtes prié de réserver un créneau horaire allant de jusqu'à un maximum {$rules['reservation_duration']}h avant l'heure de fermeture !"], 400);
        }

        if($request["time"] < $rules['min_hours'].":00"){
            return response()->json(['error' => "L'établissement : {$establishmentInfos['name']} étant ouvert de {$rules['min_hours']}h à {$rules['max_hours']}h, vous êtes prié de réserver un créneau horaire allant d'un minimum de {$rules['min_hours']}h !"], 400);
        }

        $already_reserv = DB::table('reservations')->where('date', $request['date'])->where('reservation_hours', $request['time'])->where('email', $request['email'])->count();
        if($already_reserv >= 1){
            return response()->json(['error' => "Vous avez déjà réservé un créneau horaire avec cette adresse e-mail !"], 400);
        }

        $nb_reserv = DB::table('reservations')->where('date', $request['date'])->where('reservation_hours', $request['time'])->count();
        if($nb_reserv >= 2){
            return response()->json(['error' => "Le nombre maximal de créneau disponible pour cette heure a déjà été atteinte ! Veuillez réessayer avec un autre créneau horaire !"], 400);
        }

        $params = [
            'token' => md5(uniqid(true)),
            'email' => $request['email'],
            'date' => $request['date'],
            'time' => $request['time'],
            'status' => 'réservation',
            'smiley' => '（￣︶￣）↗',
            'title' => 'Réservation',
            'subject' => "Votre réservation",
            'route' => 'api'
        ];

        $insert = DB::table('reservations')->insert([
            'token' => $params['token'],
            'date' => $params['date'],
            'email' => $params['email'],
            'reservation_hours' => $params['time'],
        ]);
        if($insert){
            Mail::to($params['email'])->send(new Res_Can_Mail($params));
            return response()->json(['success' => "Un email de confirmation vous a ete envoyee"], 200);
        }
        else{
            return response()->json(['error' => "Une erreur c'est produite lors de la réservation... Réessayer plus tard !"], 500);
        }
    }

    public function cancelReserv($token)
    {
        $reservation = DB::table('reservations')->where('token', $token)->first();
        if(!$reservation){
            return response()->json(['error' => "Impossible d'annuler la réservation... Un lien d'annulation vous a été envoyé par mail plus tôt ! Vous êtes prié de vous y reporter afin d'effectuer une nouvelle demande d'annulation ! En cas de nouvelle erreur, il se peut que vous ayez déjà annuler votre créneau !"], 500);
        }
        $params = [
            'token' => $reservation->token,
            'email' => $reservation->email,
            'date' => $reservation->date,
            'time' => $reservation->reservation_hours,
            'subject' => "Confirmation d'annulation",
            'smiley' => '/(ㄒoㄒ)/~~',
            'title' => 'Annulation',
            'status' => 'annulation'
        ];

        $deleted = DB::table('reservations')->where('token', $token)->delete();
        if($deleted){
            Mail::to($params['email'])->send(new Res_Can_Mail($params));
            return response()->json(['success' => "Nous vous confirmons la suppression de votre réservation !"], 200);
        }
        return response()->json(['error' => "Impossible d'annuler la réservation... Un lien d'annulation vous a été envoyé par mail plus tôt ! Vous êtes prié de vous y reporter afin d'effectuer une nouvelle demande d'annulation ! En cas de nouvelle erreur, il se peut que vous ayez déjà annuler votre créneau !"], 500);
    }
}
