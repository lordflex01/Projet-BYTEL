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
var imputID = 0;
var H = [];
$(document).ready(function () {
  $("#btnRech").click(function () {
    var parseDates = (inp) => {
      let year = parseInt(inp.slice(0, 4), 10);
      let week = parseInt(inp.slice(6), 10);

      let day = 1 + (week - 0) * 7; // 1st of January + 7 days for each week

      let dayOffset = new Date(year, 0, 1).getDay(); // we need to know at what day of the week the year start

      dayOffset--; // depending on what day you want the week to start increment or decrement this value. This should make the week start on a monday

      let days = [];
      for (
        let i = 0;
        i < 7;
        i++ // do this 7 times, once for every day
      )
        days.push(new Date(year, 0, day - dayOffset + i));
      // add a new Date object to the array with an offset of i days relative to the first day of the week
      return days;
    };
    var week = document.querySelector("#date-input");
    var dates = parseDates(week.value);
    let days = [];
    let Z;
    let w;
    for (let i = 0; i < 7; i++) {
      Z = dates[i].toString();
      w = Z.split(" ");
      days[i] = w[2];
    }

    //alert(days);
    var date = $("#date-input").val().split("-");
    week = date[1];
    year = date[0];

    var id = $("#name").val();
    $("#idCard").html(week);

    $.ajax({
      url: "/imput",
      type: "POST",
      dataType: "json",
      async: true,
      success: function (data, status) {
        var e = $(
          '<th><button id="addRow" type="button" class="btn btn-block btn-info btn-sm" style="width: 30px;margin-bottom: -7px;border-radius: 30px;"><i class="fa fa-plus"></i></button></th>' +
          "<th></th>" +
          '<th style="width: 40px">Lun ' +
          days[0] +
          '</th><th  style="width: 40px">Mar ' +
          days[1] +
          '</th><th  style="width: 40px">Mer ' +
          days[2] +
          '</th><th  style="width: 40px">Jeu ' +
          days[3] +
          "</th>" +
          '<th  style="width: 40px">Vend ' +
          days[4] +
          "</th>" +
          '<th  style="width: 40px;color:red">Total</th>' +
          '<th  style="width: 40px;color:green">Commentaires</th></tr>'
        );

        $("#entete").html("");
        $("#entete").append(e);
        $("#tableB").html("");

        var a = 0;
        var c = 0;
        var t = 0;
        var j = 0;

        //bool = pour afficher une seul fois le commentaire
        var bool = 0;
        //bool2 = si il ya une imputation pendant cette semaine
        var bool2 = 0;
        var obj = jQuery.parseJSON(data);

        (H[0] = 0), (H[1] = 0), (H[2] = 0), (H[3] = 0), (H[4] = 0);
        $.each(obj, function (key, value) {
          m = value.date.date.split("-");
          z = m[2].split(" ");
          W = value.week;

          //Condition pour afficher le code projet une seul fois
          if (id == value.user && week == W) {
            if (a != value.tache) {
              $("#tableB").append(
                "<td><span>Code Projet: " + value.codeprojet + "</span></td>"
              );
            }
            //Rempli le tableau qui sera afficher avec les valeur dans l'ordre
            for (let i = 0; i < 5; i++) {
              if (z[0] == days[i]) {
                H[i] = value.valeur;
                t = t + value.valeur;
              }
            }
            imputID = value.imputID;
            bool2 = 1;
            c = c + 1;
            a = value.tache;
          }
        });
        //verifie si il ya une imputation pendant cette semaine pour les afficher dans l'ordre
        if (bool2 == 1) {
          var L = $(
            "<td></td>" +
            '<td><input id="m1" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
            H[0] +
            '></td><td><input id="m2" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
            H[1] +
            '></td><td><input id="m3" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
            H[2] +
            '></td><td><input id="m4" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
            H[3] +
            '></td><td><input id="m5" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
            H[4] +
            "></td>"
          );
          $("#tableB").append(L);
        }
        if (c > 0) {
          //TOTALE
          $("#tableB").append(
            '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
            t +
            "></td>"
          );
          com = "";
          $.each(obj, function (key, value) {
            if (id == value.user && bool == 0) {
              $("#tableB").append(
                '<td><input id="com" style="max-width: 200px" type="text" class="form-control-imput" value=' +
                value.commentaire +
                "></td>" +
                '<td><i class="fa fa-edit" id="editCode" style="font-size: 16px;margin-top: 8px;cursor:pointer;color: #1586bc;"></i></td>'
              );
              bool = 1;
            }
          });
        }

        var scntDiv = $("#tableB");
        var i = $("#tableB tr").length + 1;
        //liste des tache
        var tache = [];
        while (j < obj[0].tacheliste.length) {
          tache[j] = obj[0].tacheliste[j];
          j++;
        }
        tache;

        //liste des code projet
        let f = 0;
        var codeP = [];
        while (f < obj[0].codeprojetlist.length) {
          codeP[f] = obj[0].codeprojetlist[f];
          f++;
        }
        codeP;

        $("#addRow").click(function () {
          scntDiv.append(
            '<tr><td><select class="form-control select2" id="codeP2" style="width: 100%;">' +
            "<option value = " +
            codeP[0].id +
            ">" +
            codeP[0].libelle +
            "</option>" +
            "<option value= " +
            codeP[1].id +
            ">" +
            codeP[1].libelle +
            "</option>" +
            "<option value= " +
            codeP[2].id +
            ">" +
            codeP[2].libelle +
            "</option>" +
            "<option value= " +
            codeP[3].id +
            ">" +
            codeP[3].libelle +
            "</option></select></td>" +
            '<td><select class="form-control select2" id="codeP" style="width: 100%;">' +
            "<option value = " +
            tache[0].id +
            ">" +
            tache[0].libelle +
            "</option>" +
            "<option value= " +
            tache[1].id +
            ">" +
            tache[1].libelle +
            "</option>" +
            "<option value= " +
            tache[2].id +
            ">" +
            tache[2].libelle +
            "</option>" +
            "<option value= " +
            tache[3].id +
            ">" +
            tache[3].libelle +
            "</option></select></td>" +
            '<td><input type="number" id="i1" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
            '<td><input type="number" id="i2"  min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
            '<td><input type="number" id="i3"  min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
            '<td><input type="number" id="i4" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
            '<td><input type="number" id="i5" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
            '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
            '<td><input type="text"  id="i6"  style="max-width: 200px" class="form-control-imput"></td>' +
            '<td><i class="fa fa-plus" id="addCode" style="font-size: 16px;margin-top: 8px;cursor:pointer;color: green;"></i></td>' +
            '<td><i class="fa fa-trash" id="suppCode" style="font-size: 16px;cursor:pointer;margin-top: 8px;color: #cc1919;"id="remScnt"></i></td></tr>'
          );
          i++;
          return false;
        });
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  $(document).on("click", "#addCode", function () {
    var parseDates = (inp) => {
      let year = parseInt(inp.slice(0, 4), 10);
      let week = parseInt(inp.slice(6), 10);

      let day = 1 + (week - 0) * 7; // 1st of January + 7 days for each week

      let dayOffset = new Date(year, 0, 1).getDay(); // we need to know at what day of the week the year start

      dayOffset--; // depending on what day you want the week to start increment or decrement this value. This should make the week start on a monday

      let days = [];
      for (
        let i = 0;
        i < 7;
        i++ // do this 7 times, once for every day
      )
        days.push(new Date(year, 0, day - dayOffset + i));
      // add a new Date object to the array with an offset of i days relative to the first day of the week
      return days;
    };
    let id = $("#name").val();
    var week = document.querySelector("#date-input");
    var dates = parseDates(week.value);
    let selected = $("#codeP option:selected");
    let str1 = $("#i1").val();
    let str2 = $("#i2").val();
    let str3 = $("#i3").val();
    let str4 = $("#i4").val();
    let str5 = $("#i5").val();
    let Commentaires = $("#i6").val();

    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      tache: selected[0].value,
      valeur: valeur,
      Commentaires: Commentaires,
      date: dates,
      user: id,
    };
    $.ajax({
      url: `/apii/new`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (data, status) { },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  $(document).on("click", "#editCode", function () {

    let Commentaires = $("#com").val();
    let str1 = $("#m1").val();
    let str2 = $("#m2").val();
    let str3 = $("#m3").val();
    let str4 = $("#m4").val();
    let str5 = $("#m5").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      'imputID': imputID,
      'valeur': valeur,
      'Commentaires': Commentaires,
    }

    $.ajax({
      url: `/apii/edit`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (data, status) {
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  $(document).on("click", "#suppCode", function () {
    alert("suppression");
  });
});
