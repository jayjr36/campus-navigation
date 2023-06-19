<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HOME</title>

  </script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    *{
      box-sizing: border-box;
    }
    #map {
      height: 700px;
      width: 70%;
    }
    
    .rightpane{
      height: 600px;
      width: 25%;
      float: right;
    }

    .leftpane {
      padding: 20px;
      float: left;
    }

    .pathbtn {
      padding-left: 20px;

    }

    .getpathbtn {
      padding: 10px 40px;
      background-color: lightblue;

      border: none;
      border-radius: 5px;
      font-size: 22px;
      cursor: pointer;
    }

    .search {
      padding: 10px 20px;
      background-color: lightblue;

      border: none;
      border-radius: 5px;
      font-size: 22px;
      cursor: pointer;
    }
    .frem{
      height: 200px;
      width: 300px;
      margin-top: 10px;
    }
    .labl{
      font-size: 23px;
    }
    .labl2{
      font-size: 25px;
      font-weight: 800;
    }
    a{
    
      font-size: 22px;
      font-weight: 800;
      padding: 10px;
      text-decoration: none;
      border: 2px solid black;
      background-color: lightblue;
      border-radius: 10px;
    }
    .topbar{
      padding-bottom: 10px;
      padding-top: 10px;
      background-image: linear-gradient(to right, #00ffcc, #3366ff);
      display: block;
    }
    h1{
      font-size: 65px;
    }
    
  </style>
</head>

<body>
  <div class="topbar">
  
  <center>
  <h1 style="color: white;">DIT NAVIGATION SYSTEM</h1>
  </center>
  </div>
  <div class="leftpane">
    <label class="labl">Origin:</label>
    <select id="origin" name="origin" class="labl">
      <?php
      $conn = new mysqli('localhost', 'root', '', 'navigation');
      if ($conn->connect_error) {
        die("connection failed: " . $conn->connect_error);
      }

      $sql = "SELECT DISTINCT origin FROM pathways";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<option value='" . $row['origin'] . "'>" . $row['origin'] . "</option>";
        }
      }
      $conn->close();

      ?>
    </select>

    <label class="labl">   Destination:</label>
    <select class="labl" id="destination" name="destination">
      <?php
      $conn = new mysqli('localhost', 'root', '', 'navigation');
      if ($conn->connect_error) {
        die("connection failed: " . $conn->connect_error);
      }

      $sql = "SELECT DISTINCT destination FROM pathways";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<option value='" . $row['destination'] . "'>" . $row['destination'] . "</option>";
        }
      }
      $conn->close();

      ?>
    </select>
    
    <button class="getpathbtn" id="get-path-btn">Get Path</button>
    <input class="labl"type="text" id="search" placeholder="search">
    <button class="search" id="search-btn">SEARCH</button>

  </div>
  <div class="rightpane">
    <h1>Custom search</h1>
    <p>Search by name of Lecture<p>
      <form method="POST" action="getemployee.php" target="frem">
      <input type="text" name="username" placeholder="NAME">
      <input type="submit" value="SUBMIT">
      </form>
      <iframe class="frem" name="frem"></iframe>
      <div>
      <p class="labl2" id="distance"></p>
      </div>
      
  </div>
  <div id="map"></div>
 
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9oIypXxGyJU8fbc__c5CXJPC_seo1_hk&libraries=geometry&callback=initMap&v=weekly" async defer>
  </script>

  <script>
    var map;

    function initMap() {
      map = new google.maps.Map(document.getElementById('map'), {
        center: {
          lat: -6.815282,
          lng: 39.279995
        },
        zoom: 18,
      });
    }
    /**
     // get the user's current location and center the map on it
  navigator.geolocation.getCurrentPosition(function(position) {
    var pos = {
      lat: position.coords.latitude,
      lng: position.coords.longitude
    };
    map.setCenter(pos);

    // add a marker to the user's current location
    var marker = new google.maps.Marker({
      position: pos,
      map: map
    });

    // continuously track the user's location and update the marker position
    navigator.geolocation.watchPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      marker.setPosition(pos);
    });
  });
      
     */
    $(document).ready(function() {
      var currentPathLine = null;
      var startMark = null;
      var endMark = null;
      $("#get-path-btn").click(function() {
        var origin = $("#origin").val();
        var destination = $("#destination").val();
        if (origin == "" || destination == "") {
          alert("Please select origin and destination.");
        } else {
          $.ajax({
            dataType: 'json',
            url: "getpath.php",
            method: "POST",
            data: {
              origin: origin,
              destination: destination
            },

            success: function(response) {
              var pathCoordinates = [];
              for (var i = 0; i < response.length; i++) {
                var lat = response[i].lat;
                var lng = response[i].lng;
                if (!isNaN(lat) && !isNaN(lng)) {
                  pathCoordinates.push({
                    lat: lat,
                    lng: lng
                  });
                }
              }
              // remove the current pathline from the map if there is one
              if (currentPathLine != null) {
                currentPathLine.setMap(null);
              }

              // remove the previous markers from the map if there are any
              if (startMark != null) {
                startMark.setMap(null);
              }
              if (endMark != null) {
                endMark.setMap(null);
              }
              // create markers at start and end points
              var startMarker = new google.maps.Marker({
                position: pathCoordinates[0],
                map: map,
                label: origin
              });

              var endMarker = new google.maps.Marker({
                position: pathCoordinates[pathCoordinates.length - 1],
                map: map,
                label: destination
              });

              var pathLine = new google.maps.Polyline({
                path: pathCoordinates,
                geodesic: true,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
              });

              pathLine.setMap(map);
              startMark = startMarker;
              endMark = endMarker;
              currentPathLine = pathLine;

              // calculate and display the distance between the origin and destination
              var distance = google.maps.geometry.spherical.computeDistanceBetween(startMarker.getPosition(), endMarker.getPosition());
              $("#distance").html("Distance: " + distance.toFixed(2) + " meters");


              // fetch details from database and display in info window when marker is clicked
              var infowindow = new google.maps.InfoWindow({
                maxWidth: 200
              });
              google.maps.event.addListener(startMarker, 'click', function() {
                $.ajax({
                  dataType: 'json',
                  url: "descrpt.php",
                  method: "POST",
                  data: {
                    origin: origin
                  },
                  success: function(response) {
                    var contentString = '<div>' +
                      '<h2>' + origin + '</h2>' +
                      '<p>' + response.description + '</p>' +
                      '</div>';
                    infowindow.setContent(contentString);
                    infowindow.open(map, startMarker);
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                  }
                });
              });

              google.maps.event.addListener(endMarker, 'click', function() {
                $.ajax({
                  dataType: 'text',
                  url: "descrpt.php",
                  method: "POST",
                  data: {
                    destination: destination
                  },
                  success: function(response) {

                    response = JSON.parse(response);
                    var contentString = '<div>'


                    if (response.content) {
                      contentString += '<p>' + response.content + '</p>';
                    } else {
                      contentString += '<p>No description available</p>';
                    }

                    contentString += '</div>';
                    infowindow.setContent(contentString);
                    infowindow.open(map, endMarker);
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                  }
                });
              });
            },

          });

        }
      });
    });

    // Retrieve the location name from the search box
    $(document).ready(function() {
      $("#search-btn").click(function() {

        var location_name = document.getElementById('search').value;
        if (location_name === null) {
          const confirmed = window.confirm("Please enter a search term.");
    if (!confirmed) {
        return;
    }
        } else {

          // Send an AJAX request to the server to retrieve the latitude and longitude values for the specified location
          var xhr = new XMLHttpRequest();
          xhr.open('POST', 'search.php?location=' + location_name);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.onload = function() {
            if (xhr.status === 200) {
              var response = JSON.parse(xhr.responseText);
              var latitude = parseFloat(response.latitude);
              var longitude = parseFloat(response.longitude);

              var map = new google.maps.Map(document.getElementById('map'), {
                center: {
                  lat: latitude,
                  lng: longitude
                },
                zoom: 20
              });

              var lcmarker = new google.maps.Marker({
                position: {
                  lat: latitude,
                  lng: longitude
                },
                map: map,
                title: location_name,
                label: location_name
              });

              var infowindow = new google.maps.InfoWindow({
                maxWidth: 200
              });
              google.maps.event.addListener(lcmarker, 'click', function() {
                $.ajax({
                  dataType: 'text',
                  url: "descrpt.php",
                  method: "POST",
                  data: {
                    destination: location_name
                  },
                  success: function(response) {

                    response = JSON.parse(response);
                    var contentString = '<div>'


                    if (response.content) {
                      contentString += '<p>' + response.content + '</p>';
                    } else {
                      contentString += '<p>No description available</p>';
                    }

                    contentString += '</div>';
                    infowindow.setContent(contentString);
                    infowindow.open(map, lcmarker);
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                  }
                });
              });


            } else {
              alert('Location not found');
            }
          };
          xhr.send('location=' + location_name);


        }
      })
    });
  </script>


</body>

</html>