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
          $("#tableB").append(
            '<th><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
              value.valeur +
              "></th>"
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
          '<tr><td><select id="cdprojet" class="form-control select2"><option>Douae</option></select></td>'+
          '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>'+
          '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>'+
          '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>'+
          '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>'+
          '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>'+
          '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>'+
          '<td><input type="text"  style="max-width: 200px" class="form-control-imput"></td>'+
          '<td><i class="fa fa-trash" style="font-size: 16px;margin-top: 8px;color: #cc1919;"id="remScnt"></i></td>'
        );
        i++;
        return false;
        
      });
      $(document).on('click', '#remScnt', function() {
        if (i > 2) {
            $(this).closest('tr').remove();
            i--;
        }
        return false;
    });
    $("#cdprojet").change(function () {
          alert($(this).val());
        });
    },
    error: function (xhr, textStatus, errorThrown) {
      alert(xhr.responseText);
    },
  });
});
