$(document).ready(function(){ 
    $("input[type=submit]").click(function(e) {
        e.preventDefault();
        var accion = $(this).attr('dir'),
            $form = $(this).closest('form');
        if(typeof accion !== 'undefined'){
            $form.attr('action', accion);
        }
        $form.submit();
    });
});