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
                    "<th>Code Projet</th>" +
                    "<th>Tâches</th>" +
                    "<th>Activitées</th>" +
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

                compteurligneajout = 0;
                var a = 0;
                var b = 0;
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
                        if (a != value.tache || b != value.codeprojet) {
                            nombresimputation++;
                            codprojettableau[nombresimputation - 1] = value.codeprojet;
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
                        if (imputID[nombreimputation - 1] != value.imputID || nombreimputation == 0) {
                            imputID[nombreimputation] = value.imputID;
                            nombreimputation++;
                        }
                        bool2 = 1;
                        c = c + 1;
                        b = value.codeprojet;
                        a = value.tache;
                    }
                });
                //verifie si il ya une imputation pendant cette semaine pour les afficher dans l'ordre
                if (bool2 == 1) {
                    let j = 0;
                    for (let i = 0; i < nombreimputation; i++) {
                        var LLL = $(

                            "<tr><td></td>" + "<td></td>" + "<td><span>Code Projet: " + codprojettableau[i] + "</span></td>" + '<td><input id="m1' + imputID[i] + '" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                            H[0 + j] +
                            '></td><td><input id="m2' + imputID[i] + '" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                            H[1 + j] +
                            '></td><td><input id="m3' + imputID[i] + '" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                            H[2 + j] +
                            '></td><td><input id="m4' + imputID[i] + '" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                            H[3 + j] +
                            '></td><td><input id="m5' + imputID[i] + '" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                            H[4 + j] +
                            "></td>" + '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                            t[i] +
                            "></td>" + '<td><input id="com' + i + '" style="max-width: 200px" type="text" class="form-control-imput" value=' +
                            commentairelab[i] +
                            "></td>" +
                            '<td><i class="fa fa-edit" id="editCode' + i + '" style="font-size: 16px;margin-top: 8px;cursor:pointer;color: #1586bc;"></i></td>' +
                            '<td><i class="fa fa-trash"  style="font-size: 16px;cursor:pointer;margin-top: 8px;color: #cc1919;"id="suppCode' + i + '"></i></td>' + "</tr>"

                        );
                        j = j + 5;
                        $("#tableB").append(LLL);
                    }

                    /*
                                        //une seul imputation cette semaine
                                        var LL1 = $(
                                            "<tr><td></td>" + "<td><span>Code Projet: " + codprojettableau[0] + "</span></td>" + '<td><input id="m1" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[0] +
                                            '></td><td><input id="m2" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[1] +
                                            '></td><td><input id="m3" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[2] +
                                            '></td><td><input id="m4" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[3] +
                                            '></td><td><input id="m5" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[4] +
                                            "></td>" + '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            t[0] +
                                            "></td>" + '<td><input id="com" style="max-width: 200px" type="text" class="form-control-imput" value=' +
                                            commentairelab[0] +
                                            "></td>" +
                                            '<td><i class="fa fa-edit" id="editCode" style="font-size: 16px;margin-top: 8px;cursor:pointer;color: #1586bc;"></i></td>' + "</tr>"
                    
                                        );
                                        //deux imputation cette semaine
                                        var LL2 = $("<tr><td></td>" + "<td><span>Code Projet: " + codprojettableau[1] + "</span></td>" + '<td><input id="m1" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[0 + 5] +
                                            '></td><td><input id="m2" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[1 + 5] +
                                            '></td><td><input id="m3" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[2 + 5] +
                                            '></td><td><input id="m4" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[3 + 5] +
                                            '></td><td><input id="m5" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[4 + 5] +
                                            "></td>" + '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            t[1] +
                                            "></td>" + '<td><input id="com" style="max-width: 200px" type="text" class="form-control-imput" value=' +
                                            commentairelab[1] +
                                            "></td>" +
                                            '<td><i class="fa fa-edit" id="editCode" style="font-size: 16px;margin-top: 8px;cursor:pointer;color: #1586bc;"></i></td>' + "</tr>");
                    
                                        var LL3 = $("<tr><td></td>" + "<td><span>Code Projet: " + codprojettableau[2] + "</span></td>" + '<td><input id="m1" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[0 + 10] +
                                            '></td><td><input id="m2" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[1 + 10] +
                                            '></td><td><input id="m3" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[2 + 10] +
                                            '></td><td><input id="m4" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[3 + 10] +
                                            '></td><td><input id="m5" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[4 + 10] +
                                            "></td>" + '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            t[2] +
                                            "></td>" + '<td><input id="com" style="max-width: 200px" type="text" class="form-control-imput" value=' +
                                            commentairelab[2] +
                                            "></td>" +
                                            '<td><i class="fa fa-edit" id="editCode" style="font-size: 16px;margin-top: 8px;cursor:pointer;color: #1586bc;"></i></td>' + "</tr>");
                    
                                        var LL4 = $("<tr><td></td>" + "<td><span>Code Projet: " + codprojettableau[3] + "</span></td>" + '<td><input id="m1" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[0 + 15] +
                                            '></td><td><input id="m2" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[1 + 15] +
                                            '></td><td><input id="m3" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[2 + 15] +
                                            '></td><td><input id="m4" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[3 + 15] +
                                            '></td><td><input id="m5" type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            H[4 + 15] +
                                            "></td>" + '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value=' +
                                            t[3] +
                                            "></td>" + '<td><input id="com" style="max-width: 200px" type="text" class="form-control-imput" value=' +
                                            commentairelab[3] +
                                            "></td>" +
                                            '<td><i class="fa fa-edit" id="editCode" style="font-size: 16px;margin-top: 8px;cursor:pointer;color: #1586bc;"></i></td>' + "</tr>");
                    
                                        $("#tableB").append(LL1);
                                        if (nombresimputation == 2) {
                                            $("#tableB").append(LL2);
                                        }
                                        if (nombresimputation == 3) {
                                            $("#tableB").append(LL2);
                                            $("#tableB").append(LL3);
                                        }
                                        if (nombresimputation == 4) {
                                            $("#tableB").append(LL2);
                                            $("#tableB").append(LL3);
                                            $("#tableB").append(LL4);
                                        }*/
                }
                /* if (c > 0) {
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
                 }*/

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


                $("#addRow").click(function () {
                    compteurligneajout++;
                    scntDiv.append(
                        '<tr><td><select class="form-control select2" id="codeP' + compteurligneajout + '" style="width: 100%;">' +
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
                        '<td><select class="form-control select2" id="tache' + compteurligneajout + '" style="width: 100%;">' +
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
                        "</option></select></td>" + '<td><select class="form-control select2" id="activite' + compteurligneajout + '" style="width: 100%;">' +
                        "<option value = " +
                        activite[0].id +
                        ">" +
                        activite[0].libelle +
                        "</option>" +
                        "<option value= " +
                        activite[1].id +
                        ">" +
                        activite[1].libelle +
                        "</option>" +
                        "<option value= " +
                        activite[2].id +
                        ">" +
                        activite[2].libelle +
                        "</option>" +
                        "<option value= " +
                        activite[3].id +
                        ">" +
                        activite[3].libelle +
                        "</option></select></td>" +
                        '<td><input type="number" id="i1' + compteurligneajout + '" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
                        '<td><input type="number" id="i2' + compteurligneajout + '"  min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
                        '<td><input type="number" id="i3' + compteurligneajout + '"  min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
                        '<td><input type="number" id="i4' + compteurligneajout + '" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
                        '<td><input type="number" id="i5' + compteurligneajout + '" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
                        '<td><input type="number" min="0" max="1" step="0.25" class="form-control-imput" value="0"></td>' +
                        '<td><input type="text"  id="i6' + compteurligneajout + '"  style="max-width: 200px" class="form-control-imput"></td>' +
                        '<td><i class="fa fa-plus" id="addCode" style="font-size: 16px;margin-top: 8px;cursor:pointer;color: green;"></i></td>' +
                        '<td><i class="fa fa-trash" id="suppRow" style="font-size: 16px;cursor:pointer;margin-top: 8px;color: #cc1919;"></i></td></tr>'
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
    $(document).on("click", "#save", function () {
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
        var tableauimputation = [];
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
            let ligneimputation = {
                activite: activiteselected[0].value,
                tache: tacheselected[0].value,
                codeprojet: codePselected[0].value,
                valeur: valeur,
                Commentaires: Commentaires,
                date: dates,
                user: id,
            };


            tableauimputation[i - 1] = ligneimputation;
        }

        var data = {
            tableauimput: tableauimputation,
            nbr: compteurligneajout,
        };
        $.ajax({
            url: `/apii/new`,
            type: "POST",
            processData: false,
            contentType: false,
            data: JSON.stringify(data),
            dataType: "json",
            async: true,
            success: function (data, status) { },
            error: function (xhr, textStatus, errorThrown) {
                alert(xhr.responseText);
            },
        });
    });

    //DEBUT EDIT 1er imputation
    $(document).on("click", "#editCode0", function () {

        let Commentaires = $("#com0").val();
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
            'imputID': imputID[0],
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
    //FIN EDIT 1er imputation

    //DEBUT EDIT 2eme imputation
    $(document).on("click", "#editCode1", function () {

        let Commentaires = $("#com1").val();
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
            'imputID': imputID[1],
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
    //FIN EDIT 2eme imputation

    //DEBUT EDIT 3eme imputation
    $(document).on("click", "#editCode2", function () {

        let Commentaires = $("#com2").val();
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
            'imputID': imputID[2],
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
    //FIN EDIT 3eme imputation

    //DEBUT EDIT 4eme imputation
    $(document).on("click", "#editCode3", function () {

        let Commentaires = $("#com3").val();
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
            'imputID': imputID[3],
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
    //FIN EDIT 4eme imputation

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
            success: function (data, status) { },
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
            success: function (data, status) { },
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
            success: function (data, status) { },
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
            success: function (data, status) { },
            error: function (xhr, textStatus, errorThrown) {
                alert(xhr.responseText);
            },
        });
    });
    //FIN 4er Suppression 
    //supprimer une ligne
    $(document).on("click", "#suppRow", function () {
        compteurligneajout--;
        $(this).closest("tr").remove();
    });

});
