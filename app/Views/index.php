<!DOCTYPE html>
<html>
    <head>
        <title>Web Browser</title>        
        <link rel="stylesheet" href="<?php echo base_url();?>/assets/styles/style.css">
        <link rel="stylesheet" href="<?php echo base_url();?>/assets/styles/pagination-style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <header>            
            <div class="bar">
                <img class="logo" src="<?php echo base_url();?>/assets/img/logo.png">                                
                <form method="GET" action="search"  class="search-bar">    
                    <input id="input-search" name="query" type="search" placeholder="Search the web.." required>
                    <button id="btn-search" type="submit"></button>
                </form>                                    
            </div>
        </header>
        <main>
            <div id="content">
                <!-- <article class="card">
                    <div class="pesan">
                        <h3 class="belum">Anda Belum Melakukan Pencarian</h3>
                    </div>
                </article> -->
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
                <?php endif;?>                                
            </div>           
            <div class="pager">
                <?php if ($pager) :?>                
                    <?php //$pager->setPath('search_engine/public/'); ?>
                    <?= $pager->links('no','default_full'); ?>
          
                <?php endif; ?>  
            </div>
        </main>
        <footer>
            <p>Web Browser &copy; 2020, ilham76c</p>
        </footer>
        <script src="<?php echo base_url();?>/assets/scripts/script.js"></script>
    </body>
</html>