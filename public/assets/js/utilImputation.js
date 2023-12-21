$(function () {
  $(".select2").select2();
});
var imputID = [];
imputID[0] = 0;
var H = [];
var nombresimputation = 0;
var compteurligneajout = 0;
$(document).ready(function () {
  $("#btnRech").click(function () {
    var parseDates = (inp) => {
      let year = parseInt(inp.slice(0, 4), 10);
      let week = parseInt(inp.slice(6), 10);

      let day = 1 + (week - 0) * 7; // 1st of January + 7 days for each week

      let dayOffset = new Date(year, 0, 1).getDay(); // we need to know at what day of the week the year start

      if (dayOffset == 0) {
        dayOffset = 6;
      }
      else if (dayOffset == 1) {
        dayOffset = 7;
      }
      else {
        dayOffset--;
      }
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

    var date = $("#date-input").val().split("-");
    week = date[1];
    year = date[0];

    var id = $("#name").val();
    $("#idCard").html(week);

//les selecte envoyer au controlleur
    var selectdate = [];
    selectdate[0] = id;
    selectdate[1] = week;
    selectdate[2] = year;
    $.ajax({
      url: "/imput",
      type: "POST",
data: JSON.stringify(selectdate),
      dataType: "json",
      async: true,
      success: function (data, status) {
        var e = $(
          '<th><button id="addRow" type="button" class="btn btn-block btn-info btn-sm" style="width: 30px;"><i class="fa fa-plus"></i></button></th>' +
          "<th>Code Projet</th>" +
          "<th>Tâches</th>" +
          "<th>Activités</th>" +
          '<th style="width: 40px">Lun ' +
          days[0] +
          '</th><th  style="width: 40px">Mar ' +
          days[1] +
          '</th><th  style="width: 40px">Mer ' +
          days[2] +
          '</th><th  style="width: 40px">Jeu ' +
          days[3] +
          "</th>" +
          '<th  style="width: 40px">Ven ' +
          days[4] +
          "</th>" +
          '<th  style="width: 40px;color:red">Total</th>' +
          '<th  style="width: 40px;color:green">Commentaires</th></tr>'
        );

        $("#entete").html("");
        $("#entete").append(e);

        var footer = $(
          "<th></th>" +
          '<th style="color:red">Total</th>' +
          "<th></th>" +
          "<th></th>" +
          '<td><input type="text" name="totalinput1" class="form-control-imput-totcol" disabled></td>' +
          '<td><input type="text" name="totalinput2" class="form-control-imput-totcol" disabled></td>' +
          '<td><input type="text"  name="totalinput3" class="form-control-imput-totcol"disabled></td>' +
          '<td><input type="text" name="totalinput4" class="form-control-imput-totcol" disabled></td>' +
          '<td><input type="text"  name="totalinput5" class="form-control-imput-totcol" disabled></td>' +
          '<td><input type="text" name="totalCol" class="form-control-imput-totcol" disabled></td>'
        );
        $("#footTable").html("");
        $("#footTable").append(footer);

        $("#tableB").html("");

        compteurligneajout = 0;
        var a = 0;
        var b = 0;
var d = 0;
        var c = 0;
        var t = [];
        t[0] = 0;
        var j = 0;

        //bool = pour afficher une seul fois le commentaire
        var bool = 0;
        //bool2 = si il ya une imputation pendant cette semaine
        var bool2 = 0;
        var obj = jQuery.parseJSON(data);
        nombresimputation = 0;
        let codprojettableau = [];
        let tacheteableau = [];
let tacheteableauD = [];
        let activitetableau = [];
        let nmbdedatV = 0;
        let commentairelab = [];
        let totalconteur = 0;
        let nombreimputation = 0;
        let setimput0 = [];
        setimput0[0] = 0;
        imputID = setimput0;

        (H[0] = 0), (H[1] = 0), (H[2] = 0), (H[3] = 0), (H[4] = 0);
        $.each(obj, function (key, value) {
          m = value.date.date.split("-");
          z = m[2].split(" ");
          W = value.week;
          //Condition pour remplire un tableau de code projet
          if (id == value.user && week == W) {
            if (
              a != value.tache ||
              b != value.codeprojet ||
              d != value.activite
            ) {
              nombresimputation++;
              codprojettableau[nombresimputation - 1] = value.codeprojet;
              tacheteableau[[nombresimputation - 1]] = value.tache;
tacheteableauD[[nombresimputation - 1]] = value.tacheD;
              activitetableau[nombresimputation - 1] = value.activite;
              commentairelab[nombresimputation - 1] = value.commentaire;
              /*$("#tableB").append(
                              "<td><span>Code Projet: " + value.codeprojet + "</span></td>"
                          );*/
            }
            //Rempli le tableau qui sera afficher avec les valeur dans l'ordre
            for (let i = 0; i < 5; i++) {
              if (z[0] == days[i]) {
                H[nmbdedatV] = value.valeur;
                t[totalconteur] = t[totalconteur] + value.valeur;
                if (i == 4) {
                  totalconteur++;
                  t[totalconteur] = 0;
                }
                nmbdedatV++;
              }
            }
            if (
              imputID[nombreimputation - 1] != value.imputID ||
              nombreimputation == 0
            ) {
              imputID[nombreimputation] = value.imputID;
              nombreimputation++;
            }
            bool2 = 1;
            c = c + 1;
            b = value.codeprojet;
            a = value.tache;
d = value.activite;
          }
        });
        //verifie si il ya une imputation pendant cette semaine pour les afficher dans l'ordre
        if (bool2 == 1) {
          let j = 0;
          for (let i = 0; i < nombreimputation; i++) {
            var LLL = $(
              "<tr><td></td><td><span>" +
              codprojettableau[i] +
              "</span></td>" +
              "<td><span>" +
              tacheteableau[i] +
": " +
              tacheteableauD[i] +
              "</span></td>" +
              "<td>" +
              activitetableau[i] +
              "</td>" +
              '<td><input id="m1' +
              imputID[i] +
              '" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
              H[0 + j] +
              '></td><td><input id="m2' +
              imputID[i] +
              '" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
              H[1 + j] +
              '></td><td><input id="m3' +
              imputID[i] +
              '" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
              H[2 + j] +
              '></td><td><input id="m4' +
              imputID[i] +
              '" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
              H[3 + j] +
              '></td><td><input id="m5' +
              imputID[i] +
              '" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
              H[4 + j] +
              "></td>" +
              '<td><input type="text"  name="totalRow" class="form-control-total" value=' +
              t[i] +
              " disabled></td>" +
              '<td><input id="com' +
              i +
              '" style="max-width: 200px" type="text" class="form-control-imput" value=' +
              commentairelab[i] +
              "></td>" +
              '<td><i class="fa fa-trash"  style="font-size: 16px;cursor:pointer;margin-top: 8px;color: #cc1919;"id="suppCode' +
              i +
              '"></i></td>' +
              "</tr>"
            );
            j = j + 5;

            $("#tableB").append(LLL);
          }
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

        //liste des activité
        let act = 0;
        var activite = [];
        while (act < obj[0].activitelist.length) {
          activite[act] = obj[0].activitelist[act];
          act++;
        }
        activite;

        var footer = $(
          "<tr><td></td>" +
          "<td><span style='width: 40px;color:red;font-weight:bold'>Total</span></td>" +
          "<td></td>" +
          "<td></td>" +
          '<td style="width: 40px"><input type=text" class="form-control-imput"  id="totalCol"></td>' +
          '<td style="width: 40px"><input type="text" class="form-control-imput" id="totalCol"></td>' +
          '<td style="width: 40px"><input type="text" class="form-control-imput" id="totalCol"></td>' +
          '<td style="width: 40px"><input type="text" class="form-control-imput" id="totalCol"></td>' +
          '<td style="width: 40px"><input type="text" class="form-control-imput" id="totalCol"></td>' +
          '<td  style="width: 40px;color:red"></td>' +
          '<td  style="width: 40px;color:green"></td></tr>'
        );

        // $("#footTable").html("");
        //$("#footTable").append(footer);

        $("#addRow").click(function () {
          compteurligneajout++;
          var add =
            '<tr><td></td><td><select class="form-control select2" onchange="remplirSelect2(' +
            compteurligneajout +
            ');" name="select1' +
            compteurligneajout +
            '"  id="codeP' +
            compteurligneajout +
            '" style="width: 201px"><option>--Select--</option>';
          for (liste = 0; liste < codeP.length; liste++) {
            add +=
              "<option value = " +
              codeP[liste].id +
              ">" +
              codeP[liste].libelle +
              ": " +
              codeP[liste].description;
            ("</option>");
          }
          add +=
            "</select></td>" +
            '<td><select name="select2' +
            compteurligneajout +
            '" class="form-control-imputation select2" style="width: 120px;" id="tache' +
            compteurligneajout +
            '"><option>--Select--</option>';
          for (liste = 0; liste < tache.length; liste++) {
            add +=
              "<option value = " +
              tache[liste].id +
              ">" +
              tache[liste].libelle +
              ": " +
              tache[liste].description;
            ("</option>");
          }
          add +=
            "</select></td>" +
            '<td><select class="form-control-imputation select2" style="width: 120px;" id="activite' +
            compteurligneajout +
            '"><option>--Select--</option>';
          for (liste = 0; liste < activite.length; liste++) {
            add +=
              "<option value = " +
              activite[liste].id +
              ">" +
              activite[liste].libelle +
              "</option>";
          }
          add +=
            "</select></td>" +
            '<td><input type="number" id="i1' +
            compteurligneajout +
            '" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
            '<td><input type="number" id="i2' +
            compteurligneajout +
            '"  min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
            '<td><input type="number" id="i3' +
            compteurligneajout +
            '"  min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
            '<td><input type="number" id="i4' +
            compteurligneajout +
            '" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
            '<td><input type="number" id="i5' +
            compteurligneajout +
            '" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
            "<td><input type='text' name='totalRow' class='form-control-total'></td>" +
            '<td><input type="text"  id="i6' +
            compteurligneajout +
            '"  style="max-width: 150px" class="form-control-imput"></td>' +
            '<td><i class="fa fa-trash" id="suppRow" style="font-size: 16px;cursor:pointer;margin-top: 8px;color: #cc1919;"></i></td></tr>';

          scntDiv.append(add);
$("#codeP" + compteurligneajout).select2({ dropdownAutoWidth: true });
          $("#tache" + compteurligneajout).select2({ dropdownAutoWidth: true });
          i++;
          return false;
        });

        //calculer le total ligne
},
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });

        $(document).on("click", ".form-control-imput", function () {
          var $tr = $(this).closest("tr");
          var tot = 0;
          $(".form-control-imput", $tr).each(function () {
            tot += Number($(this).val()) || 0;
          });
          $("input[name='totalRow']", $tr).val(tot);
        });
      
  $(document).on("click", "input[id^=i1],input[id^=m1]", function () {
    var calculated_total_sum = 0;
    $("input[id^=i1],input[id^=m1]").each(function () {
      var get_textbox_value = $(this).val();
      if ($.isNumeric(get_textbox_value)) {
        calculated_total_sum += parseFloat(get_textbox_value);
      }
    });
    $("input[name='totalinput1']").val(calculated_total_sum);
  });


  $(document).on("click", "input[id^=i2],input[id^=m2]", function () {
    var calculated_total_sum = 0;
    $("input[id^=i2],input[id^=m2]").each(function () {
      var get_textbox_value = $(this).val();
      if ($.isNumeric(get_textbox_value)) {
        calculated_total_sum += parseFloat(get_textbox_value);
      }
    });
    $("input[name='totalinput2']").val(calculated_total_sum);
  });

  $(document).on("click", "input[id^=i3],input[id^=m3]", function () {
    var calculated_total_sum = 0;
    $("input[id^=i3],input[id^=m3]").each(function () {
      var get_textbox_value = $(this).val();
      if ($.isNumeric(get_textbox_value)) {
        calculated_total_sum += parseFloat(get_textbox_value);
      }
    });
    $("input[name='totalinput3']").val(calculated_total_sum);
  });

  $(document).on("click", "input[id^=i4],input[id^=m4]", function () {
    var calculated_total_sum = 0;
    $("input[id^=i4],input[id^=m4]").each(function () {
      var get_textbox_value = $(this).val();
      if ($.isNumeric(get_textbox_value)) {
        calculated_total_sum += parseFloat(get_textbox_value);
      }
    });
    $("input[name='totalinput4']").val(calculated_total_sum);
  });

  $(document).on("click", "input[id^=i5],input[id^=m5]", function () {
    var calculated_total_sum = 0;
    $("input[id^=i5],input[id^=m5]").each(function () {
      var get_textbox_value = $(this).val();
      if ($.isNumeric(get_textbox_value)) {
        calculated_total_sum += parseFloat(get_textbox_value);
      }
    });
    $("input[name='totalinput5']").val(calculated_total_sum);
  });

  $(document).on("click", ".form-control-imput", function () {
    var tot = 0;
    $(".form-control-imput").each(function () {
      tot += Number($(this).val()) || 0;
    });
    $("input[name='totalCol']").val(tot);
  });

  $(document).on("click", "#save", function () {
    var parseDates = (inp) => {
      let year = parseInt(inp.slice(0, 4), 10);
      let week = parseInt(inp.slice(6), 10);

      let day = 1 + (week - 0) * 7; // 1st of January + 7 days for each week

      let dayOffset = new Date(year, 0, 1).getDay(); // we need to know at what day of the week the year start

      if (dayOffset == 0) {
        dayOffset = 6;
      }
      else if (dayOffset == 1) {
        dayOffset = 7;
      }
      else {
        dayOffset--;
      }

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
    ////////////////////////////DEBUT MODIFICATION/////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////
    var tableaumodif = [];
    var tabcumuleimputM = [];
    (tabcumuleimputM[0] = 0),
      (tabcumuleimputM[1] = 0),
      (tabcumuleimputM[2] = 0),
      (tabcumuleimputM[3] = 0),
      (tabcumuleimputM[4] = 0);

    for (let i = 0; i < imputID.length; i++) {
      let CommentairesM = $("#com" + i + "").val();
      let str1M = $("#m1" + imputID[i] + "").val();
      let str2M = $("#m2" + imputID[i] + "").val();
      let str3M = $("#m3" + imputID[i] + "").val();
      let str4M = $("#m4" + imputID[i] + "").val();
      let str5M = $("#m5" + imputID[i] + "").val();
      let valeurM = [];
      (valeurM[0] = str1M),
        (valeurM[1] = str2M),
        (valeurM[2] = str3M),
        (valeurM[3] = str4M),
        (valeurM[4] = str5M);

      (tabcumuleimputM[0] = tabcumuleimputM[0] + parseFloat(valeurM[0])),
        (tabcumuleimputM[1] = tabcumuleimputM[1] + parseFloat(valeurM[1])),
        (tabcumuleimputM[2] = tabcumuleimputM[2] + parseFloat(valeurM[2])),
        (tabcumuleimputM[3] = tabcumuleimputM[3] + parseFloat(valeurM[3])),
        (tabcumuleimputM[4] = tabcumuleimputM[4] + parseFloat(valeurM[4]));

      var modification = {
        imputID: imputID[i],
        valeur: valeurM,
        Commentaires: CommentairesM,
      };
      tableaumodif[i] = modification;
    }
    var dataM = {
      tableaumodif: tableaumodif,
      nbrmodification: imputID.length,
      tabcumuleimput: tabcumuleimputM,
    };
    ///////////////////////////FIN MODIFICATION////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////
    var tableauimputation = [];
    var tabcumuleimput = [];
    (tabcumuleimput[0] = 0),
      (tabcumuleimput[1] = 0),
      (tabcumuleimput[2] = 0),
      (tabcumuleimput[3] = 0),
      (tabcumuleimput[4] = 0);

    for (let i = 1; i <= compteurligneajout; i++) {
      let id = $("#name").val();
      var week = document.querySelector("#date-input");
      var dates = parseDates(week.value);
      let tacheselected = $("#tache" + i + " option:selected");
      let codePselected = $("#codeP" + i + " option:selected");
      let activiteselected = $("#activite" + i + " option:selected");
      dates[0].setHours(dates[0].getHours() + 2);
      dates[1].setHours(dates[1].getHours() + 2);
      dates[2].setHours(dates[2].getHours() + 2);
      dates[3].setHours(dates[3].getHours() + 2);
      dates[4].setHours(dates[4].getHours() + 2);
      let str1 = $("#i1" + i + "").val();
      let str2 = $("#i2" + i + "").val();
      let str3 = $("#i3" + i + "").val();
      let str4 = $("#i4" + i + "").val();
      let str5 = $("#i5" + i + "").val();
      let Commentaires = $("#i6" + i + "").val();

      let valeur = [];
      (valeur[0] = str1),
        (valeur[1] = str2),
        (valeur[2] = str3),
        (valeur[3] = str4),
        (valeur[4] = str5);
      (tabcumuleimput[0] = tabcumuleimput[0] + parseFloat(valeur[0])),
        (tabcumuleimput[1] = tabcumuleimput[1] + parseFloat(valeur[1])),
        (tabcumuleimput[2] = tabcumuleimput[2] + parseFloat(valeur[2])),
        (tabcumuleimput[3] = tabcumuleimput[3] + parseFloat(valeur[3])),
        (tabcumuleimput[4] = tabcumuleimput[4] + parseFloat(valeur[4]));

      let ligneimputation = {
        activite: activiteselected[0].value,
        tache: tacheselected[0].value,
        codeprojet: codePselected[0].value,
        valeur: valeur,
        Commentaires: Commentaires,
        date: dates,
        user: id,
        tabcumuleimput: tabcumuleimput,
        tabcumuleimputM: tabcumuleimputM,
      };

      tableauimputation[i - 1] = ligneimputation;
    }

    var data = {
      tableauimput: tableauimputation,
      nbr: compteurligneajout,
    };

    //EDIT
    $.ajax({
      url: `/apii/edit`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(dataM),
      dataType: "json",
      async: true,
      success: function (response) {
        if (response == 200) {
          var Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 6000,
          });

          Toast.fire({ icon: "success", title: " Modification confirmée . " });
        }
        if (response == 202) {
          var Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 6000,
          });

          Toast.fire({
            icon: "error",
            title: " Une des modifications est > 1 dans une même journée . ",
          });
        }
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });

    //NEW
    $.ajax({
      url: `/apii/new`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(data),
      dataType: "json",
      async: true,
      success: function (response) {
        if (response == 200) {
          var Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 6000,
          });

          Toast.fire({ icon: "success", title: " Imputation Confirmée . " });
          $("#btnRech").click();
        }
        if (response == 201) {
          var Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 6000,
          });

          Toast.fire({
            icon: "error",
            title: " Un des couples tâche et code projet existe déjà . ",
          });
        }
        if (response == 202) {
          var Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 6000,
          });

          Toast.fire({
            icon: "error",
            title: " Une des imputations est > 1 dans une même journée. ",
          });
        }
      },
      error: function (xhr, textStatus, errorThrown) {
        if (
          xhr.responseJSON.type ==
          "https://tools.ietf.org/html/rfc2616#section-10"
        ) {
          var Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 6000,
          });
          Toast.fire({
            icon: "error",
            title: " Vous avez oublié de remplir l'une des activités. ",
          });
        } else alert(xhr.responseText);
      },
    });
  });

  //DEBUT 1er suppression
  $(document).on("click", "#suppCode0", function () {
    let str1 = $("#m1" + imputID[0] + "").val();
    let str2 = $("#m2" + imputID[0] + "").val();
    let str3 = $("#m3" + imputID[0] + "").val();
    let str4 = $("#m4" + imputID[0] + "").val();
    let str5 = $("#m5" + imputID[0] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[0],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 1er Suppression

  //DEBUT 2er suppression
  $(document).on("click", "#suppCode1", function () {
    let str1 = $("#m1" + imputID[1] + "").val();
    let str2 = $("#m2" + imputID[1] + "").val();
    let str3 = $("#m3" + imputID[1] + "").val();
    let str4 = $("#m4" + imputID[1] + "").val();
    let str5 = $("#m5" + imputID[1] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[1],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 2er Suppression

  //DEBUT 3er suppression
  $(document).on("click", "#suppCode2", function () {
    let str1 = $("#m1" + imputID[2] + "").val();
    let str2 = $("#m2" + imputID[2] + "").val();
    let str3 = $("#m3" + imputID[2] + "").val();
    let str4 = $("#m4" + imputID[2] + "").val();
    let str5 = $("#m5" + imputID[2] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[2],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 3er Suppression

  //DEBUT 4er suppression
  $(document).on("click", "#suppCode3", function () {
    let str1 = $("#m1" + imputID[3] + "").val();
    let str2 = $("#m2" + imputID[3] + "").val();
    let str3 = $("#m3" + imputID[3] + "").val();
    let str4 = $("#m4" + imputID[3] + "").val();
    let str5 = $("#m5" + imputID[3] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[3],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 4er Suppression
//DEBUT 5er suppression
  $(document).on("click", "#suppCode4", function () {
    let str1 = $("#m1" + imputID[4] + "").val();
    let str2 = $("#m2" + imputID[4] + "").val();
    let str3 = $("#m3" + imputID[4] + "").val();
    let str4 = $("#m4" + imputID[4] + "").val();
    let str5 = $("#m5" + imputID[4] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[4],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 5er Suppression
  //DEBUT 6er suppression
  $(document).on("click", "#suppCode5", function () {
    let str1 = $("#m1" + imputID[5] + "").val();
    let str2 = $("#m2" + imputID[5] + "").val();
    let str3 = $("#m3" + imputID[5] + "").val();
    let str4 = $("#m4" + imputID[5] + "").val();
    let str5 = $("#m5" + imputID[5] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[5],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 6er Suppression
  //DEBUT 7er suppression
  $(document).on("click", "#suppCode6", function () {
    let str1 = $("#m1" + imputID[6] + "").val();
    let str2 = $("#m2" + imputID[6] + "").val();
    let str3 = $("#m3" + imputID[6] + "").val();
    let str4 = $("#m4" + imputID[6] + "").val();
    let str5 = $("#m5" + imputID[6] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[6],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 7er Suppression
  //DEBUT 8er suppression
  $(document).on("click", "#suppCode7", function () {
    let str1 = $("#m1" + imputID[7] + "").val();
    let str2 = $("#m2" + imputID[7] + "").val();
    let str3 = $("#m3" + imputID[7] + "").val();
    let str4 = $("#m4" + imputID[7] + "").val();
    let str5 = $("#m5" + imputID[7] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[7],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 8er Suppression
  //DEBUT 9er suppression
  $(document).on("click", "#suppCode8", function () {
    let str1 = $("#m1" + imputID[8] + "").val();
    let str2 = $("#m2" + imputID[8] + "").val();
    let str3 = $("#m3" + imputID[8] + "").val();
    let str4 = $("#m4" + imputID[8] + "").val();
    let str5 = $("#m5" + imputID[8] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[8],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 9er Suppression
  //DEBUT 10er suppression
  $(document).on("click", "#suppCode9", function () {
    let str1 = $("#m1" + imputID[9] + "").val();
    let str2 = $("#m2" + imputID[9] + "").val();
    let str3 = $("#m3" + imputID[9] + "").val();
    let str4 = $("#m4" + imputID[9] + "").val();
    let str5 = $("#m5" + imputID[9] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[9],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 10er Suppression
  //DEBUT 11er suppression
  $(document).on("click", "#suppCode10", function () {
    let str1 = $("#m1" + imputID[10] + "").val();
    let str2 = $("#m2" + imputID[10] + "").val();
    let str3 = $("#m3" + imputID[10] + "").val();
    let str4 = $("#m4" + imputID[10] + "").val();
    let str5 = $("#m5" + imputID[10] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[10],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 11er Suppression
  //DEBUT 12er suppression
  $(document).on("click", "#suppCode11", function () {
    let str1 = $("#m1" + imputID[11] + "").val();
    let str2 = $("#m2" + imputID[11] + "").val();
    let str3 = $("#m3" + imputID[11] + "").val();
    let str4 = $("#m4" + imputID[11] + "").val();
    let str5 = $("#m5" + imputID[11] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[11],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 12er Suppression
  //DEBUT 13er suppression
  $(document).on("click", "#suppCode12", function () {
    let str1 = $("#m1" + imputID[12] + "").val();
    let str2 = $("#m2" + imputID[12] + "").val();
    let str3 = $("#m3" + imputID[12] + "").val();
    let str4 = $("#m4" + imputID[12] + "").val();
    let str5 = $("#m5" + imputID[12] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[12],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 13er Suppression
  //DEBUT 14er suppression
  $(document).on("click", "#suppCode13", function () {
    let str1 = $("#m1" + imputID[13] + "").val();
    let str2 = $("#m2" + imputID[13] + "").val();
    let str3 = $("#m3" + imputID[13] + "").val();
    let str4 = $("#m4" + imputID[13] + "").val();
    let str5 = $("#m5" + imputID[13] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[13],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 14er Suppression
  //DEBUT 15er suppression
  $(document).on("click", "#suppCode14", function () {
    let str1 = $("#m1" + imputID[14] + "").val();
    let str2 = $("#m2" + imputID[14] + "").val();
    let str3 = $("#m3" + imputID[14] + "").val();
    let str4 = $("#m4" + imputID[14] + "").val();
    let str5 = $("#m5" + imputID[14] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[14],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 15er Suppression
  //DEBUT 16er suppression
  $(document).on("click", "#suppCode15", function () {
    let str1 = $("#m1" + imputID[15] + "").val();
    let str2 = $("#m2" + imputID[15] + "").val();
    let str3 = $("#m3" + imputID[15] + "").val();
    let str4 = $("#m4" + imputID[15] + "").val();
    let str5 = $("#m5" + imputID[15] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[15],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 16er Suppression
  //DEBUT 17er suppression
  $(document).on("click", "#suppCode16", function () {
    let str1 = $("#m1" + imputID[16] + "").val();
    let str2 = $("#m2" + imputID[16] + "").val();
    let str3 = $("#m3" + imputID[16] + "").val();
    let str4 = $("#m4" + imputID[16] + "").val();
    let str5 = $("#m5" + imputID[16] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[16],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 17er Suppression
  //DEBUT 18er suppression
  $(document).on("click", "#suppCode17", function () {
    let str1 = $("#m1" + imputID[17] + "").val();
    let str2 = $("#m2" + imputID[17] + "").val();
    let str3 = $("#m3" + imputID[17] + "").val();
    let str4 = $("#m4" + imputID[17] + "").val();
    let str5 = $("#m5" + imputID[17] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[17],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 18er Suppression
  //DEBUT 19er suppression
  $(document).on("click", "#suppCode18", function () {
    let str1 = $("#m1" + imputID[18] + "").val();
    let str2 = $("#m2" + imputID[18] + "").val();
    let str3 = $("#m3" + imputID[18] + "").val();
    let str4 = $("#m4" + imputID[18] + "").val();
    let str5 = $("#m5" + imputID[18] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[18],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 19er Suppression
  //DEBUT 20er suppression
  $(document).on("click", "#suppCode19", function () {
    let str1 = $("#m1" + imputID[19] + "").val();
    let str2 = $("#m2" + imputID[19] + "").val();
    let str3 = $("#m3" + imputID[19] + "").val();
    let str4 = $("#m4" + imputID[19] + "").val();
    let str5 = $("#m5" + imputID[19] + "").val();
    let valeur = [];
    (valeur[0] = str1),
      (valeur[1] = str2),
      (valeur[2] = str3),
      (valeur[3] = str4),
      (valeur[4] = str5);
    let test = {
      imputID: imputID[19],
    };

    $.ajax({
      url: `/apii/delete`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(test),
      dataType: "json",
      async: true,
      success: function (response) {
        var Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
        });

        Toast.fire({ icon: "success", title: " Suppression Confirmée . " });
        $("#btnRech").click();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
      },
    });
  });
  //FIN 20er Suppression
  //supprimer une ligne
  $(document).on("click", "#suppRow", function () {
    compteurligneajout--;
    $(this).closest("tr").remove();
  });
});
//////////////////////////////////////////////////////////////////////////////////////////
///////////////////////Fonction Export/////////////////////////////////////////////////////
$(document).ready(function () {
  $("#export").click(function () {
    var parseDates = (inp) => {
      let year = parseInt(inp.slice(0, 4), 10);
      let week = parseInt(inp.slice(6), 10);

      let day = 1 + (week - 0) * 7; // 1st of January + 7 days for each week

      let dayOffset = new Date(year, 0, 1).getDay(); // we need to know at what day of the week the year start

      if (dayOffset == 0) {
        dayOffset = 6;
      }
      else if (dayOffset == 1) {
        dayOffset = 7;
      }
      else {
        dayOffset--;
      }

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

    var date = $("#date-input").val().split("-");
    week = date[1];
    year = date[0];
    day = dates[0];

    $("#idCard").html(week);
    dates[0].setHours(dates[0].getHours() + 2);
    let data = {
      week: week,
      year: year,
      dates: dates,
    };

    $.ajax({
      url: `/export`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(data),
      dataType: "json",
      async: true,
      success: function (responseText) { },
      error: function (xhr, textStatus, errorThrown) {
        let filename = "data_semaine.csv";
        let csvFile = new Blob(["\uFEFF" + xhr.responseText], {
          type: "text/csv",
        });
        let downloadLink = document.createElement("a");
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        document.body.appendChild(downloadLink);
        downloadLink.click();
      },
    });
  });
});
//////////////////////////////////////////////////////////////////////////////////////////
///////////////////////Fonction Export mois/////////////////////////////////////////////////////
$(document).ready(function () {
  $("#exportMois").click(function () {
    var parseDates = (inp) => {
      let year = parseInt(inp.slice(0, 4), 10);
      let week = parseInt(inp.slice(6), 10);

      let day = 1 + (week - 0) * 7; // 1st of January + 7 days for each week

      let dayOffset = new Date(year, 0, 1).getDay(); // we need to know at what day of the week the year start

      if (dayOffset == 0) {
        dayOffset = 6;
      }
      else if (dayOffset == 1) {
        dayOffset = 7;
      }
      else {
        dayOffset--;
      }

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

    var date = $("#date-input").val().split("-");
    week = date[1];
    year = date[0];
    day = dates[0];

    $("#idCard").html(week);
    dates[0].setHours(dates[0].getHours() + 2);
    let data = {
      week: week,
      year: year,
      dates: dates,

    }

    $.ajax({
      url: `/exportmois`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(data),
      dataType: "json",
      async: true,
      success: function (responseText) { },
      error: function (xhr, textStatus, errorThrown) {
        let filename = "data_mois.csv";
        let csvFile = new Blob(["\uFEFF" + xhr.responseText], {
          type: "text/csv",
        });
        let downloadLink = document.createElement("a");
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        document.body.appendChild(downloadLink);
        downloadLink.click();
      },
    });
  });
});
//////////////////////////////////////////////////////////////////////////////////////////
///////////////////////Fonction Export intervalle/////////////////////////////////////////////////////
$(document).ready(function () {
  $("#exportIntervalle").click(function(){
    $("#dataRangeModal").modal('show');
  });
  $("#exportDataRangeModal").on('click', function(){
    var dateDebut = $('#date_debut_modal').val();
    var dateFin = $('#date_fin_modal').val();
    let data = {
      dateDebut: dateDebut,
      dateFin: dateFin,
    };

    $.ajax({
      url: `/exportinter`,
      type: "POST",
      processData: false,
      contentType: false,
      data: JSON.stringify(data),
      dataType: "json",
      async: true,
      success: function (responseText) { },
      error: function (xhr, textStatus, errorThrown) {
        let filename = "data_intervalle.csv";
        let csvFile = new Blob(["\uFEFF" + xhr.responseText], {
          type: "text/csv",
        });
        let downloadLink = document.createElement("a");
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        document.body.appendChild(downloadLink);
        downloadLink.click();
      },
    });
  });
});


//////////////////////////////////////////////////////////////////////////////////////////
///////////////////////Fonction select2/////////////////////////////////////////////////////

function remplirSelect2(x) {
  var id_select = $('select[name="select1' + x + '"]').val();
  let selectCode = {
    id: id_select,
  };
  $.ajax({
    url: "/remplirSelect2",
    type: "POST",
    data: JSON.stringify(selectCode),
    dataType: "json",
    async: true,
    success: function (response) {
      let v = JSON.parse(response);
      $('select[name="select2' + x + '"]').html("");
      $.each(v, function (index, value) {
        // et  boucle sur la réponse contenu dans la variable passé à la function du success "json"
        $('select[name="select2' + x + '"]').append(
          '<option value="' +
          value.id +
          '">' +
          value.libelle +
          ": " +
          value.description +
          "</option>"
        );
      });
    },
    error: function (xhr, textStatus, errorThrown) {
      alert(xhr.responseText);
    },
  });
}
