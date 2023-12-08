<?php

class Homepage extends View{

    public function body(){
        ?>
        <!-- a home page that lusts all the series -->
        <div class="homepage">
            <div class="all_series">
                <?php
                $series = $this->app->get_series();
                foreach( $series as $series ){
                    $series_name = $series->get_name();
                    $series_url = $series->get_url();
                    ?>
                    <div class="series">
                        <a href="<?php echo $series_url; ?>"><?php echo $series_name; ?></a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?
    }
}