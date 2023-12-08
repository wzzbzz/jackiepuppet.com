<?php

class Channelpage extends View{

    public function render(){
        ?><!-- a home page that losts all the channels -->
        <div class="homepage">
            <div class="channels">
                <?php
                $channels = $this->app->get_channels();
                foreach( $channels as $channel ){
                    $channel_name = $channel->get_name();
                    $channel_url = $channel->get_url();
                    ?>
                    <div class="channel">
                        <a href="<?php echo $channel_url; ?>"><?php echo $channel_name; ?></a>
                    </div>
                    <?php
                }
                ?>
            </div>
        <?
    }
}