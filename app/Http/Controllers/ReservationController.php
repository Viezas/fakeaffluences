<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservRequest;
use App\Mail\Res_Can_Mail;
use Config\information;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function view(){
        $infos = information::reservationRulls();
        $pageTitle = 'Réservation';
        return view('reservation', compact('infos','pageTitle'));
    }

    public function reserv(ReservRequest $request)
    {
        $params = [
            'token' => md5(uniqid(true)),
            'email' => $request->get('email'),
            'date' => $request->get('date'),
            'time' => $request->get('time'),
            'status' => 'réservation',
            'smiley' => '（￣︶￣）↗',
            'title' => 'Réservation',
            'subject' => "Votre réservation",
            'route' => "web"
        ];

        $rules = information::reservationRulls();
        $establishmentInfos = information::establishmentInfos();

        if(!(strlen($params["time"]) == 5) || !str_contains($params["time"], ':00')){
            return redirect('reservation')->with('denied', "Le créneau horaire doit etre au format suivant : hh:00 !")->withInput();
        }

        if(in_array(date("D", strtotime($params["date"])), $rules['dayClose'])){
            return redirect('reservation')->with('denied', "Le jour de réservation ne figure pas parmis les jours disponibles !")->withInput();
        }

        if(date('H').':00' >= ($rules['max_hours'] - $rules['reservation_duration']).":00" && date('Y-m-d') >= $params['date']){
            return redirect('reservation')->with('denied', "L'heure et/ou la date de réservation de votre créneau est invalid !")->withInput();
        }

        if($params["time"] > ($rules['max_hours'] - $rules['reservation_duration']).":00"){
            return redirect('reservation')->with('denied', "L'établissement : {$establishmentInfos['name']} étant ouvert de {$rules['min_hours']}h à {$rules['max_hours']}h, vous êtes prié de réserver un créneau horaire allant de jusqu'à un maximum {$rules['reservation_duration']}h avant l'heure de fermeture !")->withInput();
        }

        if($params["time"] < $rules['min_hours'].":00"){
            return redirect('reservation')->with('denied', "L'établissement : {$establishmentInfos['name']} étant ouvert de {$rules['min_hours']}h à {$rules['max_hours']}h, vous êtes prié de réserver un créneau horaire allant d'un minimum de {$rules['min_hours']}h !")->withInput();
        }

        $already_reserv = DB::table('reservations')->where('date', $params['date'])->where('reservation_hours', $params['time'])->where('email', $params['email'])->count();
        
        if($already_reserv >= 1){
            return redirect('reservation')->with('denied', "Vous avez déjà réservé un créneau horaire avec cette adresse e-mail !")->withInput();
        }

        $nb_reserv = DB::table('reservations')->where('date', $params['date'])->where('reservation_hours', $params['time'])->count();

        if($nb_reserv >= 2){
            return redirect('reservation')->with('denied', "Le nombre maximal de créneau disponible pour cette heure a déjà été atteinte ! Veuillez réessayer avec un autre créneau horaire !")->withInput();
        }

        DB::table('reservations')->insert([
            'token' => $params['token'],
            'date' => $params['date'],
            'email' => $params['email'],
            'reservation_hours' => $params['time'],
        ]);

        Mail::to($params['email'])->send(new Res_Can_Mail($params));
        $pageTitle = 'Accueil';
        $token = $params['token'];
        return view('welcome', compact('establishmentInfos', 'pageTitle', 'token'));
    }

    public function cancelReserv($token)
    {
        $reservation = DB::table('reservations')->where('token', $token)->first();
        if(!$reservation){
            return redirect('/')->with('denied', "Impossible d'annuler la réservation... Un lien d'annulation vous a été envoyé par mail plus tôt ! Vous êtes prié de vous y reporter afin d'effectuer une nouvelle demande d'annulation ! En cas de nouvelle erreur, il se peut que vous ayez déjà annuler votre créneau ! ");
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
            return redirect('/')->with('deleted', "Nous vous confirmons la suppression de votre réservation !");
        }
        return redirect('/')->with('denied', "Impossible d'annuler la réservation... Un lien d'annulation vous a été envoyé par mail plus tôt ! Vous êtes prié de vous y reporter afin d'effectuer une nouvelle demande d'annulation ! En cas de nouvelle erreur, il se peut que vous ayez déjà annuler votre créneau ! ");
    }
}
