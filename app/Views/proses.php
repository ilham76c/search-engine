<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
</head>
<body data-spy="scroll" data-target="#nav-menu" data-offset="0">
    <nav id="nav-menu" class="navbar sticky-top navbar-expand-sm navbar-dark bg-dark">        
        <ul class="navbar-nav nav-pills">
            <li class="nav-item">
                <a class="nav-link" href="#proses1">Proses 1</a>
            </li class="nav-item">
            <li class="nav-item">
                <a class="nav-link" href="#proses2">Proses 2</a>
            </li class="nav-item">
            <li class="nav-item">
                <a class="nav-link" href="#proses3">Proses 3</a>
            </li class="nav-item">
            <li class="nav-item">
                <a class="nav-link" href="#proses4">Proses 4</a>
            </li class="nav-item">
            <li class="nav-item">
                <a class="nav-link" href="#proses5">Proses 5</a>
            </li class="nav-item">
        </ul>        
    </nav>   
    <div class="container">                
        <div id="proses1" class="container-fluid pt-5">
            <h3 class="font-weight-bold p-2 text-center">Proses Query Expansion</h3>
            <?php echo $proses_query_expansion;?>                  
        </div>
        
        <div id="proses2" class="container-fluid pt-5">
            <h3 class="font-weight-bold p-2 text-center">Proses preprocessing query</h3>
            <?php echo $proses_preprocessing_query;?>            
        </div>

        <section id="proses3" class="container-fluid pt-5">
            <h3 class="font-weight-bold p-2 text-center">Proses Pembobotan TF-IDF Query</h3>
            <?php echo $proses_pembobotan_query;?>         
        </section>

        <section id="proses4" class="container-fluid pt-5">        
            <h3 class="font-weight-bold p-2 text-center">Proses Mengambil Dokumen yang memiliki term pada query</h3>
            <?php echo $proses_pengambilan_dokumen;?>           
        </section>
        
        <section id="proses5" class="container-fluid pt-5">
            <h3 class="font-weight-bold p-2 text-center">Hasil perhitungan cosine similarity</h3>
            <?php echo $proses_perangkingan_dokumen;?>           
        </section>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>