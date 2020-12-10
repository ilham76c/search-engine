<!DOCTYPE html>
<html>
    <head>
        <title>Web Browser</title>        
        <link rel="stylesheet" href="<?= base_url();?>/assets/styles/style.css">
        <link rel="stylesheet" href="<?= base_url();?>/assets/styles/pagination-style.css">
        <link rel="stylesheet" href="<?= base_url();?>/assets/styles/button-style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">       
    </head>
    <body>
        <header>            
            <div class="bar">
                <div class="logo">
                    <img id="gbr" src="<?= base_url();?>/assets/img/logo.png">                                
                </div>
                
                <form method="GET" action="<?= site_url('search')?>"  class="search-bar">    
                    <input id="input-search" name="query" type="search" placeholder="Search the web.." value="<?= old('query');?>" required>
                    <button id="btn-search" type="submit"></button>
                </form>      
                <div class="btn-container">
                    <input id="btn-switch" name="btn-switch" type="checkbox" <?= (session('btn_QE') == 'true') ? 'checked' : '';?>>   
                    <a id="btn-proses" href="<?= site_url('proses')?>" target="_blank">
                        Proses
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                </div>
            </div>
        </header>
        <main>
            <div id="content">                
                <?php if($documents): ?>
                    <?php foreach($documents as $dokumen): ?>                
                        <article class="card">
                            <a class="title" href="<?php echo $dokumen->url;?>"><h4 class="title"><?php echo $dokumen->title;?></h4></a>
                            <small><a class="url" href="<?php echo $dokumen->url;?>"><?php echo $dokumen->url;?></a></small>                    
                            <div class="wrap">
                                <a class="description" href="<?php echo $dokumen->url;?>">
                                    <p><?php echo $dokumen->description;?></p>
                                </a>
                            </div>                                        
                        </article>        
                    <?php endforeach; ?>
                <?php else:?>
                    <article>
                        <div class="pesan">
                            <h3 class="belum">Pencarian/kata kunci tidak ditemukan !!</h3>
                        </div>
                    </article>
                <?php endif;?>                                
                <div class="pager">
                <?php if ($pager) :?>                
                    <?php //$pager->setPath('search_engine/public/'); ?>
                    <?= $pager->links('no','default_full'); ?>
          
                <?php endif; ?>  
            </div>
            </div>                       
        </main>
        <footer>
            <p>Web Browser &copy; 2020, ilham76c</p>
        </footer>
        <script src="<?= base_url();?>/assets/bower_components/jquery/dist/jquery.min.js"></script>        
        <script src="<?= base_url();?>/assets/scripts/script.js"></script>
        <script>
            $(document).ready(function() {
                $('#btn-switch').on('click', function() {                    
                    console.log((this.checked) ? 1 : 0);                    
                    $.post(`button/${this.checked}`,function(response){
                        console.log(response);
                    });
                });
            });            
        </script>
    </body>
</html>