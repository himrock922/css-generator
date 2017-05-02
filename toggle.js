function entryChange(){
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
window.onload = entryChange;

window.onload = function () {
        document.querySelector('#register').addEventListener('submit', function (e) {
            if (!confirm("登録してもよろしいですか？")) {
                e.preventDefault();
            }
        });
    };
