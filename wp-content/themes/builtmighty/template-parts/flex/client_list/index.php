<?php 
global $flex_content;
$heading = $flex_content['heading'];
?>
<section class="client-list">
    <div class="container container-lg flex col afc jfc">
        <?php if(!empty($heading)): ?>
            <h2 class="client-list__heading"><?php echo $heading; ?></h2>
        <?php endif; ?>
        <div class="flex row afc jfsb">
            <?php 
            $client_list = array(
                array(
                    'name' => 'Funko',
                    'url' => 'https://www.funko.com/',
                    'logo' => 'clients/funko.svg',
                ),
                array(
                    'name' => 'Redfin',
                    'url' => 'https://www.redfin.com/',
                    'logo' => 'clients/redfin.svg',
                ),
                array(
                    'name' => 'Seattle Seahawks',
                    'url' => 'https://www.seahawks.com/',
                    'logo' => 'clients/seahawks.svg',
                ),
                array(
                    'name' => 'Mod',
                    'url' => 'https://modpizza.com/',
                    'logo' => 'clients/mod.svg',
                ),
                array(
                    'name' => 'TalkingRain',
                    'url' => 'https://www.talkingrain.com/',
                    'logo' => 'clients/talkingrain.svg',
                ),
                array(
                    'name' => 'Cupcake Royale',
                    'url' => 'https://www.cupcakeroyale.com/',
                    'logo' => 'clients/cupcakeroyale.svg',
                ),
                array(
                    'name' => 'Speedtree',
                    'url' => 'https://speedtree.com/',
                    'logo' => 'clients/speedtree.svg',
                ),
                array(
                    'name' => 'Seymour Duncan',
                    'url' => 'https://www.seymourduncan.com/',
                    'logo' => 'clients/seymourduncan.svg',
                ),
                array(
                    'name' => 'Discogs',
                    'url' => 'https://www.discogs.com/',
                    'logo' => 'clients/discogs.svg',
                ),
                array(
                    'name' => 'Dan Carlin',
                    'url' => 'https://www.dancarlin.com/',
                    'logo' => 'clients/dancarlin.svg',
                ),
                array(
                    'name' => 'Fluidigm',
                    'url' => 'https://www.fluidigm.com/',
                    'logo' => 'clients/fluidigm.svg',
                ),
                array(
                    'name' => 'Grundéns',
                    'url' => 'https://grundens.com/',
                    'logo' => 'clients/grundens.svg',
                ),
            );
            foreach($client_list as $client): ?>
                <figure class="client-list__client item_1_6 flex row afc jfc">
                    <?php render_svg($client['logo'], _wp_to_kebab_case($client['name'])); ?>
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>