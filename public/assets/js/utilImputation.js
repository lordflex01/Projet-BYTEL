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
  $.ajax({
    url: '/imput',
    type: 'POST',
    dataType: 'json',
    async: true,
    success: function (data, status) {
      var e = $('<th></th><th style="width: 40px">Lun</th><th  style="width: 40px">Mar</th><th  style="width: 40px">Mer</th><th  style="width: 40px">Jeu</th>' +
        '<th  style="width: 40px">Vend</th>' +
        '<th style="width: 40px">Sam</th>' +
        '<th  style="width: 40px">Dim</th><th  style="width: 40px;color:red">Total</th>');
      $('#entete').html('');
      $('#entete').append(e);
      var tb = $('<div style="background-color:red;width: 169%; padding:7px;">semaine</div>');
      $('#tableB').html('');
      $('#tableB').append(tb);
      alert(data);
      for (i = 0; i < data.length; i++) {
        imput = data[i];
        var e = $('<tr><td id = "user"></td><td id = "dateVS"></td></tr>');

        $('#user', e).html(imput['user']);
        //$('#dateVS', e).html(imput['dateVs']);
        $('#tableB').append(e);
      }
    },
    error: function (xhr, textStatus, errorThrown) {
      alert(xhr.responseText);
    }
  });

});
var date = $("#date-input").val().split("-");
month = date[1];
year = date[0];
var id = $("#name").val();
$("#idCard").html(month);