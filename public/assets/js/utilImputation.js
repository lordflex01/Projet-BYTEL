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
  var id = $("#name").val();
  $("#idCard").html(month);
  $.ajax({
    url: "/imput",
    type: "POST",
    data: { field1: month, field2: id },
    dataType: "json",
    async: true,
    success: function (data, status) {
      var e = $(
        '<tr><th></th><th style="width: 40px">Lun</th><th  style="width: 40px">Mar</th><th  style="width: 40px">Mer</th><th  style="width: 40px">Jeu</th>' +
          '<th  style="width: 40px">Vend</th>' +
          '<th style="width: 40px">Sam</th>' +
          '<th  style="width: 40px">Dim</th><th  style="width: 40px;color:red">Total</th></tr>'
      );
      $("#entete").html("");
      $("#entete").append(e);

      var div1 = $(
        '<div style="background-color:red"><tr><td><span>Semaine</span></td></tr></div>');
     
      var es = $(
        '<tr><td><span>Code Projet</span></td><td style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td>' +
          '<td  style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td>' +
          '<td  style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td>' +
          '<td  style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td>' +
          '<td  style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td>' +
          '<td style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td>' +
          '<td  style="width: 40px"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td>'+
         ' <td  style="width: 40px;color:red"><input type="number" min="0" max="1" step="0.25" class="form-control-imput"></td></tr>'
      );

      var div2 = $(
        '<tr><td><button type="button" style="width: 125%;"class="btn btn-outline-info btn-block btn-sm"><i class="fa fa-plus"></i>&nbsp;Ajouter une nouvelle imputation</button></td></tr>');
     
      $("#tableB").html("");
      $("#tableB").append(div1);
      $("#tableB").append(es);
      $("#tableB").append(div2);

      var obj = jQuery.parseJSON(data);
      $.each(obj, function (key, value) {
        if (id == value.user) {
          $("#tableB").append(
           // $('<tr><td id = "user">' + value.user + "</td></tr>")
          );
        }
      });
    },
    error: function (xhr, textStatus, errorThrown) {
      alert(xhr.responseText);
    },
  });
});