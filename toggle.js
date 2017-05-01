function entryChange1(){
    radio = document.form.select_three.checked;

    if(radio == true) {
        //フォーム
            console.log(radio);
        document.getElementById('select_tag').style.display = "";
    } else {
        //フォーム
        document.getElementById('select_tag').style.display = "none";
    }
}
//オンロードさせ、リロード時に選択を保持
window.onload = entryChange1;