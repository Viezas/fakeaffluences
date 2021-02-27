function initMap() {
  const paris = { lat:   49.089602278815796, lng: 2.171276729296133 };
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 4,
    center: paris,
  });
  const marker = new google.maps.Marker({
    position: paris,
    map: map,
  });
}