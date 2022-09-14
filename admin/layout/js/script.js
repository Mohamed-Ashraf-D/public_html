
//dashboard------------------------

$('.toggle-info').click(function () {
    $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(100);
    if ($(this).hasClass('selected')){
        $(this).html('<i class="fa fa-plus fa-lg"></i>')
    }
    else {
        $(this).html('<i class="fa fa-minus fa-lg"></i>')
    }
});
//end of dash board=------------------------
$(function () {
    'use strict';
$('[placeholder]').focus(function () {
$(this).attr('data-text', $(this).attr('placeholder'));
$(this).attr('placeholder', '');
}).blur(function () {
     $(this).attr('placeholder', $(this).attr('data-text'));
});
});
//add astrix(*) for all required field
$('input').each(function () {
if($(this).attr('required')==='required'){
    $(this).after('<span class="astrix">*</span>');
}
});
//show password if eye is found and hover with mouse
var passField=$('.password');
$('.show-pass').hover(function () {
      passField.attr('type','text');
},function () {
    passField.attr('type','password');
});
//confirm to delete
$('.confirm').click(function () {
return confirm('Are you sure you want to delete this item?');
});
//display classic
$('.cat h3').click(function () {
   $(this).next('.full-view').fadeToggle(200);
});
$('.option span').click(function () {
   $(this).addClass('active').siblings('span').removeClass('active');
   if($(this).data('view')==='Full'){
       $('.full-view').fadeIn(200);
   }else{
       $('.full-view').fadeOut(200);
   }
});
$('.child-link').hover(function () {
    $(this).find('.show-delete').fadeIn(100);
},function () {
    $(this).find('.show-delete').fadeOut(0);
})


//trigger select to select box it
$('select').selectBoxIt(
    {
        autoWidth:false
    }
);
//image to direct load live in edit or select
$('.live').keyup(function () {
    $($(this).data('class')).text($(this).val());
})
var loadFile = function(event) {
    var image = document.getElementById('output');
    image.src = URL.createObjectURL(event.target.files[0]);
};
//count down for seconds;

var timeleft = 5;
var downloadTimer = setInterval(function(){
  if(timeleft<=0 || timeleft==0 || timeleft==-1 || timeleft>5 ){
    clearInterval(downloadTimer);
    window.clearInterval(downloadTimer);
    
  }
  
  $(".alert-count").each(function(){
    $(this).text(timeleft);}) 
//   $(document).ready(function(){$('#alert-count').html(10-timeleft)});
  timeleft -=1;
  
  
}, 1000);

//calculate sum service val from table

///////////////////////////////////////reception page
//append service in table service on click add
//renumberrows
function renumberrows() {
    var rowsCount = $('#student tr');
   // console.log(rowsCount.length);
    for (var n = rowsCount.length; n >0; n--) {
        var firstCol = rowsCount[n-1].firstChild; //n=3
        //console.log(firstCol);
        for(j=n;j<rowsCount.length+2;j++) // n=4 j<5 // n=3 j<5 
        firstCol.innerText = j-n ; // j=5
        
    }
}
$(function(){
    renumberRows();
})
var rowsCount = $('.default-number tr');
$('#studennums').append(`<span> (${rowsCount.length}) </span>`)
function renumberRows() {
    var rowsCount = $('.default-number tr');
   // console.log(rowsCount.length);
   
    for (var n = 0; n <rowsCount.length; n++) {
        var firstCol = rowsCount[n].firstChild; //n=3
        firstCol.innerText=n+1;
        // console.log(firstCol);
        // n=4 j<5 // n=3 j<5 
        // firstCol.innerText=n ; // j=5
        // $('.student-id').each(function(n){
        //     $(this).text(n+1);
        //     // console.log($(this).text(n));
        // })
        // console.log(n);
        
    }
}
/////////////////////start upload file
function _(abc) {
    return document.getElementById(abc);
}

function uploadFileHandler() {
    var file = _("uploadingfile").files[0];
    var formdata = new FormData();
    formdata.append("uploadingfile", file);
    var ajax = new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandler, false);
    ajax.addEventListener("load", completeHandler, false);
    ajax.addEventListener("error", errorHandler, false);
    ajax.addEventListener("abort", abortHandler, false);
    ajax.open("POST", "test-video-upload.php");
    ajax.send(formdata);
}

function progressHandler(event) {
    var loaded = new Number((event.loaded / 1048576));//Make loaded a "number" and divide bytes to get Megabytes
    var total = new Number((event.total / 1048576));//Make total file size a "number" and divide bytes to get Megabytes
    _("uploaded_progress").innerHTML = "تم تحميل " + loaded.toPrecision(5) + "  ميجا بايتس من" + total.toPrecision(5);//String output
    var percent = (event.loaded / event.total) * 100;//Get percentage of upload progress
    _("progressBar").value = Math.round(percent);//Round value to solid
    _("statusP").innerHTML = Math.round(percent) + "% uploaded";//String output
}

function completeHandler(event) {
    _("statusP").innerHTML = event.target.responseText;//Build and show response text
    _("progressBar").value = 0;//Set progress bar to 0
    document.getElementById('progressDiv').style.display = 'none';//Hide progress bar
}

function errorHandler(event) {
    _("statusP").innerHTML = "Upload Failed";//Switch status to upload failed
}

function abortHandler(event) {
    _("statusP").innerHTML = "Upload Aborted";//Switch status to aborted
}
///////////////////////////////////////////////////end upload file

///////////////////search live using ajax

$(function(){

    $('#live-search').keyup(function(){
        let input=$(this).val();
        
        // alert(input);
        
        // console.log(renumberRows());
        if(input!="" && input.length>3){
            $.ajax({
                url:'student_data.php',
                method:'POST',
                data:{input:input},
                success:function(data){
                    $('#student').html(data);
                    // renumberRows();
                    console.log('data');
                    
                }
            })
        }else{
            // window.location.href = window.location.href;

        }
        
    })
})
// new add lessson validation
$(function(){
    $('#add-lesson').on('click',function(){
        if($('#section').val()==""){
            
            Swal.fire('اختر الفصل الدراسى');
        }
        if($('#class').val()==""){
            
            Swal.fire('اختر الصف الدراسى');
        }
    })
})

