<?php

class Seriespage extends View{

    public function body(){
        $series = $this->app->get_series();
        $series_name = $series->get_name();
        $series_url = $series->get_url();
        ?>
        <div class="seriespage">
            <div class="series">
                <a href="<?php echo $series_url; ?>"><?php echo $series_name; ?></a>
            </div>
        </div>
        <?php
    }
}