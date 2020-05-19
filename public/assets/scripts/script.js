// code agar user bisa mengklik button cari dengan tombol 'ENTER'
var input = document.getElementById('input-search');
input.addEventListener('keyup', function(event){
    if (event.keyCode === 13) {
        document.getElementById('btn-search').click();
    }
});