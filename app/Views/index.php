<!DOCTYPE html>
<html>
    <head>
        <title>Search Engine</title>
        <link rel="stylesheet" href="<?php echo base_url();?>/assets/styles/tampilan-awal-style.css">
    </head>
    <body>        
        <div class="jos">
            <div class="bar">
                <div class="logo" >
                    <img id="gbr" src="<?php echo base_url();?>/assets/img/logo.png" alt="logo">
                    <h1>GJoss</h1>
                </div>
                <form class="search-bar" method="GET" action="<?php echo base_url();?>/search">    
                    <div class="auto">
                        <input id="input-search" name="query" type="search" placeholder="Search the web.." required>
                        <button id="btn-search" type="submit"></button>
                    </div>
                </form>                                    
            </div>
        </div>
    </body>
</html>