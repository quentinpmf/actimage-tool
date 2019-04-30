//ajoute une ligne
function addRow() {
    var number = document.getElementsByClassName("infoRow").length;
    var projectSelection = "";

    <!-- lignes suivantes -->
    var contentRow = ''+
        '<div class="row infoRow number-'+number+'" id="'+number+'">' +
        '<div class="col-12 col-md-6 col-xl-2 pr-xl-3 pt-md-3">' +
        '<div class="card rounded">' +
        '<div class="card-body">' +
        '<select name="projet-'+number+'">' +
        '<option value="april_tma">April - TMA</option>' +
        '<option value="april_evols">April - Evols</option>' +
        '<option value="batigere_tma">Batigère - TMA</option>' +
        '<option value="batigere_evols">Batigère - Evols</option>' +
        '<option value="ena_tma">ENA - TMA</option>' +
        '<option value="ena_evols">ENA - Evols</option>' +
        '<option value="glh_tma">GLH - TMA</option>' +
        '<option value="glh_evols">GLH - Evols</option>' +
        '<option value="inao_tma">INAO - TMA</option>' +
        '<option value="inao_evols">INAO - Evols</option>' +
        '<option value="inao_princes_tma">INAO Princes - TMA</option>' +
        '<option value="inao_princes_evols">INAO Princes - Evols</option>' +
        '<option value="vnf_tma">VNF - TMA</option>' +
        '<option value="vnf_evols">VNF - Evols</option>' +
        '<option value="an-parlement-des-enfants_projet">AN - Parlement des enfants - Projet</option>' +
        '</select>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">' +
        '<div class="card rounded">' +
        '<div class="card-body center">' +
        '<input type="text" data-id="'+number+'" name="issue_number-'+number+'" size="5" value="#" onfocusout="getIssueSubject($(this))"/>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">' +
        '<div class="card rounded">' +
        '<div class="card-body center">' +
        '<input type="checkbox" class="conforme_redmine" id="'+number+'" name="conforme_redmine-'+number+'" size="5" checked/>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">' +
        '<div class="card rounded">' +
        '<div class="card-body p-6 center">' +
        '<input type="text" class="passed_time" id="'+number+'" name="passed_time-'+number+'" size="5"/>' +
        '</div>' +
        '</div><!--//card-->' +
        '</div>' +
        '<div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">' +
        '<div class="card rounded">' +
        '<div class="card-body p-6 center">' +
        '<input type="text" class="allocated_time" id="'+number+'" name="allocated_time-'+number+'" size="5"/>' +
        '</div>' +
        '</div><!--//card-->' +
        '</div>' +
        '<div class="col-12 col-md-6 col-xl-2 pr-xl-3 pt-md-3">' +
        '<div class="card rounded">' +
        '<div class="card-body">' +
        '<select name="state-'+number+'">' +
        '<option value="en_developpement">En développement</option>' +
        '<option value="en_recette_preprod">En recette - en préproduction</option>' +
        '<option value="en_production">En production</option>' +
        '</select>' +
        '</div>' +
        '</div><!--//card-->' +
        '</div>' +
        '<div class="col-12 col-md-6 col-xl-3 pr-xl-3 pt-md-3">' +
        '<div class="card rounded">' +
        '<div class="card-body p-6 center">' +
        '<textarea name="description-'+number+'" rows="2" cols="40" placeholder="Description"></textarea>' +
        '<input type="text" class="remarques" name="remarque-'+number+'" size="40" placeholder="Remarques"/>' +
        '</div>' +
        '</div><!--//card-->' +
        '</div>' +
        '<div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3 remove">' +
        '<div class="card rounded">' +
        '<div class="card-body p-6 center">' +
        '<a href="" onclick="remove($(this)); return false;"><img src="https://img.icons8.com/metro/26/000000/trash.png"></centera>' +
        '</div>' +
        '</div><!--//card-->' +
        '</div>' +
        '</div>';

    //récupération des values déja entrées dans les lignes plus haut
    $("input[type=text]").each(function(){
        $(this).attr('value', function (i, val) { return $(this).val(); });
    });
    $('input[type=checkbox],input[type=radio]').attr('checked', function () { return this.checked; });
    $('textarea').html(function () { return this.value; });
    $('select').find(':selected').attr('selected', 'selected');

    var rowsDiv = document.getElementById("rows");
    rowsDiv.innerHTML = rowsDiv.innerHTML + contentRow;

    $('html,body').animate({scrollTop: $("#addRow").offset().top}, 'slow');
}

//supprime la ligne
function remove(element) {
    var parentRowId = element.parent().parent().parent().parent().attr('id');
    console.log('parentRowId = ',parentRowId);
    document.getElementById(parentRowId).remove();
}

//vérifie si le temps passé ne dépasse pas 1, sinon color les bordures en rouge
function verificationPassedTime()
{
    var totalTime = 0;
    var times= document.getElementsByClassName("passed_time");
    [].forEach.call(times, function(el) {
        totalTime = totalTime+(parseFloat(el.value));
    });

    if( (parseFloat(totalTime)) > 1) {
        [].forEach.call(times, function(el) {
            el.setAttribute('style', 'background-color:#FF7272;color:white');
        });
        return false;
    }
    else {
        [].forEach.call(times, function(el) {
            el.setAttribute('style', 'background-color:;color:black');
        });
        return true;
    }

}

//enregistre le formulaire
function save() {
    if(verificationPassedTime())
    {
        var formDatas = $("#formGenerate").serialize();
        console.log('formDatas = ',formDatas);
        $.ajax({
            type: "POST",
            url: "save.php",
            data: formDatas,
        }).done(function( msg ) {
            console.log("Sauvegardé !"+msg);
            var location = window.location.href;
            if( location.includes("isSaved")) {
                document.location.href=location;
            }
            else{
                document.location.href=location+"&isSaved=1";
            }

        });
        document.getElementById("danger-alert").setAttribute('style', 'display:none');
    }
    else {
        document.getElementById("danger-alert").setAttribute('style', 'display:block');
    }
}

function parseHtmlEntities(str) {
    return str.replace(/&#([0-9]{1,3});/gi, function(match, numStr) {
        var num = parseInt(numStr, 10); // read num as normal number
        return String.fromCharCode(num);
    });
}

function getIssueSubject(object) {
    var lineId = object[0].dataset.id;
    var issueId = object[0].value.replace('#','');

    document.getElementsByName('allocated_time-'+lineId)[0].value = "";
    document.getElementsByName('description-'+lineId)[0].value = "";

    document.getElementById("loading").setAttribute('style', 'display:block');

    //récupération du nom de la demande + temps estimé
    $.ajax({
        type:'POST',
        url:'assets/php-redmine-api/getInfos.php',
        dataType: "json",
        data:{issueId:issueId},
        success:function(data){
            if(data && data['total_estimated_hours'] != "" && data['subject'] != "" ) {
                console.log('description = ',parseHtmlEntities(data['subject']));
                var x = document.getElementsByName('description-'+lineId)[0].value = parseHtmlEntities(data['subject']);
                console.log('allocated_time = ',data['total_estimated_hours']);
                document.getElementsByName('allocated_time-'+lineId)[0].value = data['total_estimated_hours'];
            }
        }
    });

    setTimeout("hideSpinner()", 1000); // après 1 sec
}

function hideSpinner() {
    document.getElementById("loading").setAttribute('style', 'display:none');
}

//valide le formulaire
function generate() {
    var form = document.getElementById("formGenerate");
    form.submit();
}

//s'active au clic sur la checkbox "Conforme redmine" : Désactivé
function checkboxConformeRedmine(element) {
    var checked = element.checked; //true ou false
    var element_passed_time = $('input[name=passed_time-'+element.id+']')[0];
    var element_allocated_time = $('input[name=allocated_time-'+element.id+']')[0]

    if(checked){
        element_passed_time.disabled = false;
        element_allocated_time.disabled = false;
    }else
    {
        element_passed_time.disabled = true;
        element_allocated_time.disabled = true
    }
}

function datepickerOnChange(event){
    newDate = event.target.value;
    document.location.href="index.php?imputation_date="+newDate;
}

//cache success-alert en 3sec
setTimeout(function() {
    $('#success-alert').fadeOut('slow');
}, 3000); // <-- 3sec

function addDatePickerReporting(event){

    //ajout d'une ligne
    var number = document.getElementsByClassName("congesFeriesLine").length;
    var projectSelection = "";

    <!-- lignes suivantes -->
    var contentRow = ''+
        '<div class="card-body nocolor withoutspace congesFeriesLine">' +
        '<input onchange="datepickerReporting(event);" name="conges_feries-'+number+'" type="date"/>&nbsp;' +
        '<input type="text" name="justificatif-conges-'+number+'" placeholder="Justificatif" size="50"/>'
        '</div>'
    ;

    //récupération des values déja entrées dans les lignes plus haut
    $("input[type=date]").each(function(){
        $(this).attr('value', function (i, val) { return $(this).val(); });
    });

    $("input[type=text]").each(function(){
        $(this).attr('value', function (i, val) { return $(this).val(); });
    });
    $('input[type=checkbox],input[type=radio]').attr('checked', function () { return this.checked; });
    $('textarea').html(function () { return this.value; });
    $('select').find(':selected').attr('selected', 'selected');

    var congesFeriesDiv = document.getElementById("conges_feries");
    congesFeriesDiv.innerHTML = congesFeriesDiv.innerHTML + contentRow;
}

function changerUserRole(event){

    document.getElementById("loading").setAttribute('style', 'display:block');

    var selectedIndex = event['0'].options.selectedIndex + 1;
    var selectName = event['0'].name;
    var splitSelectName = selectName.split('user_role-');
    var userId = splitSelectName["1"];

    console.log(userId+'/'+selectedIndex);
    //changement du role de l'utilisateur
    $.ajax({
        type:'POST',
        url:'changeUserRole.php',
        dataType: "json",
        data:{userId:userId, newRole:selectedIndex},
        success:function(data){
            console.log('data = ',data);
            if(data) {
                console.log('Changement de role pour '+userId+' ('+selectedIndex+')');
                document.location.href=window.location.href; //redirection vers page actuelle
            }
        }
    });
}