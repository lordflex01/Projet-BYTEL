$(function () {
  $(".select2").select2();
});
function exportTo(type) {
  $(".table").tableExport({
    filename: "table_%DD%-%MM%-%YY%",
    format: type,
    cols: "2,3,4",
  });
}

function exportAll(type) {
  $(".table").tableExport({
    filename: "table_%DD%-%MM%-%YY%-month(%MM%)",
    format: type,
  });
}

$("#btnRech").click(function () {
  let parseDates = (inp) => {
    let year = parseInt(inp.slice(0, 4), 10);
    let week = parseInt(inp.slice(6), 10);

    let day = (1 + (week - 1) * 7); // 1st of January + 7 days for each week

    let dayOffset = new Date(year, 0, 1).getDay(); // we need to know at what day of the week the year start

    dayOffset--; // depending on what day you want the week to start increment or decrement this value. This should make the week start on a monday

    let days = [];
    for (let i = 0; i < 7; i++)  // do this 7 times, once for every day
      days.push(new Date(year, 0, day - dayOffset + i));
    // add a new Date object to the array with an offset of i days relative to the first day of the week
    return days;
  }
  let week = document.querySelector('#date-input');
  let dates = parseDates(week.value);
  //alert(dates);
  var date = $("#date-input").val().split("-");
  week = date[1];
  year = date[0];
  day = date[2];
  var id = $("#name").val();
  $("#idCard").html(week);
  $.ajax({
    url: "/imput",
    type: "POST",
    dataType: "json",
    async: true,
    success: function (data, status) {
      var e = $(
        '<th><button id="addScnt" type="button" class="btn btn-block btn-info btn-sm" style="width: 30px;margin-bottom: -7px;border-radius: 30px;"><i class="fa fa-plus"></i></button></th><th style="width: 40px">Lun</th><th  style="width: 40px">Mar</th><th  style="width: 40px">Mer</th><th  style="width: 40px">Jeu</th>' +
        '<th  style="width: 40px">Vend</th>' +
        '<th  style="width: 40px;color:red">Total</th>' +
        '<th  style="width: 40px;color:green">Commentaires</th>'
      );

      $("#entete").html("");
      $("#entete").append(e);
      
      $("#tableB").html("");
      var a = 0;
      var c = 0;
      var t = 0;
      var bool = 0;
      var obj = jQuery.parseJSON(data);

      $.each(obj, function (key, value) {
        m = value.date.date.split("-");
        W = value.week;
        if (id == value.user && week == W) {
          if (a != value.tache) {
            $("#tableB").append(
              "<th><span>Code Projet: " + value.codeprojet + "</span></th>"
            );
          }
          var L = $(
            '<th><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
            value.valeur +
            '></th>'
          );
          $("#tableB").append(L
          );

          c = c + 1;
          a = value.tache;
          t = t + value.valeur;
        }
      });
      if (c > 0) {
        // IMPUT VIDE
        while (c < 5) {
          $('#tableB').append('<th><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></th>');
          c = c + 1;
        }
        //TOTALE
        $('#tableB').append('<th><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' + t + '></th>');
        com = "";
        $.each(obj, function (key, value) {
          if (id == value.user && bool == 0) {
            $('#tableB').append('<th><input style="max-width: 200px" type="text" class="form-control-imput" value=' + value.commentaire + '></th>');
            bool = 1;
          }
        });
      }
      var scntDiv = $("#tableB");
      var i = $("#tableB tr").length + 1;
      $("#addScnt").click(function () {
        scntDiv.append(
          '<tr><td><select class="form-control select2"><option>Douae</option></select></td>' +
          '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
          '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
          '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
          '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
          '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
          '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
          '<td><input type="text"  style="max-width: 200px" class="form-control-imput"></td>' +
          '<td><i class="fa fa-trash" style="font-size: 16px;margin-top: 8px;color: #cc1919;"id="remScnt"></i></td>'
        );
        i++;
        return false;
        //DEBUT change

        $(document).on('eventChange', (L) => {
          /* let url = `/api/${e.event.id
             }/edit`
           let donnees = {
             "valeur": e.event.title
           }
           let xhr = new XMLHttpRequest
           xhr.open("PUT", url)
           xhr.send(JSON.stringify(donnees))*/
        }

        )
        //FIN Changes
      });
      $(document).on('click', '#remScnt', function () {
        if (i > 2) {
          $(this).closest('tr').remove();
          i--;
        }
        return false;
      });

    },
    error: function (xhr, textStatus, errorThrown) {
      alert(xhr.responseText);
    },
  });
});
