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
  var date = $("#date-input").val().split("-");
  month = date[1];
  year = date[0];
  day = date[2];
  var id = $("#name").val();
  $("#idCard").html(month);
  $.ajax({
    url: '/imput',
    type: 'POST',
    data: { field1: month, field2: id },
    dataType: 'json',
    async: true,
    success: function (data, status) {
      var e = $('<th></th><th style="width: 40px">Lun</th><th  style="width: 40px">Mar</th><th  style="width: 40px">Mer</th><th  style="width: 40px">Jeu</th>' +
        '<th  style="width: 40px">Vend</th>' +
        '<th  style="width: 40px;color:red">Total</th>' + '<th  style="width: 40px;color:green">Commentaire</th>');
      $('#entete').html('');
      $('#entete').append(e);
      var div1 = $(
        '<div style="background-color:red"><tr><td><span>Semaine</span></td></tr></div>');
      var es = $(
        '<tr><td><span>Code Projet</span></td><td style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td>' +
        '<td  style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td>' +
        '<td  style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td>' +
        '<td  style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td>' +
        '<td  style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td>' +
        '<td style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td>' +
        '<td  style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td>' +
        ' <td  style="width: 40px;color:red"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td></tr>'
      );
      var div2 = $(
        '<tr><td><button  style="width: 125%;"class="btn btn-outline-info btn-block btn-sm"><i class="fa fa-plus"></i>&nbsp;Ajouter une nouvelle imputation</button></td></tr>');
      $("#tableB").html("");
      // $("#tableB").append(div1);

      //$('#tableB').append(es);
      //alert(data);
      var a = 0;
      var c = 0;
      var t = 0;
      var bool = 0;
      var obj = jQuery.parseJSON(data);
      $.each(obj, function (key, value) {
        m = value.date.date.split('-');
        // if (id == value.user && month == m[1] && year == m[0]) {
        W = value.week;
        if (id == value.user && month == W) {
          if (a != value.tache) {
            $('#tableB').append('<th><span>Code Projet: ' + value.codeprojet + '</span></th>');
          }
          $('#tableB').append('<th style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' + value.valeur + '></th>');

          c = c + 1;
          a = value.tache;
          t = t + value.valeur;
          //'<tr><td id = "user">' + value.codeprojet + ' ' + value.tache + '</td><td id = "date">' + value.date.date + '</td><td id = "valeur">' + value.valeur + '</td></tr>'
        }
      });

      if (c == 0) {
      }
      else {
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
            $('#tableB').append('<th><input style="max-width: 280px" type="text" class="form-control-imput" value=' + value.commentaire + '></th>');
            bool = 1;
          }
        });
      }

      for (i = 0; i < data.length; i++) {
        imput = data[i];
        var e = $('<tr><td id = "user"></td><td id = "dateVS"></td></tr>');

        $('#user', e).html(imput['user']);
        //$('#dateVS', e).html(imput['dateVs']);
        $('#student').append(e);
      }
    },
    error: function (xhr, textStatus, errorThrown) {
      alert(xhr.responseText);
    }
  });

});
