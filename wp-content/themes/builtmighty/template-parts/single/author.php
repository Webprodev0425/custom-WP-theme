<?php
// render author bio for posts
if (!function_exists('render_author_bio')) :
    function render_author_bio($authors_list)
    {
        foreach ($authors_list as $author) {
            $authorID = $author['author_id'];
            $author_name = $author['name'];
            $first_name = explode(" ", $author_name)[0];
            $avatar = get_avatar($authorID);
            $bio = $author['description'];
            $link = get_author_posts_url($authorID);
            $website = $author['website'];
            if ($author["type"] === "guest-author") {
                $link = get_site_url() . "/author/" . $author["slug"];
            }
?>
            <section class="flex row afc author-bio">
                <figure class="item_100">
                    <?php echo $avatar; ?>
                </figure>
                <div class="bio item_less_100">
                    <header class="meta">
                        <h4 itemprop="author" itemscope itemtype="https://schema.org/Person">
                            <?php if ($link) {
                                echo '<a href="' . $link . '" itemprop="name">' . $author_name . '</a>';
                            } else {
                                echo $author_name;
                            }
                            ?>
                        </h4>
                        <meta content="" itemprop="publisher" itemscope itemtype="https://schema.org/Organization" itemref="publisherName publisherLogo publisherLogoUrl" />
                        <meta id="publisherLogo" itemprop="logo" itemscope itemType="https://schema.org/ImageObject" content="" itemref="publisherLogoUrl" />
                        <link id="publisherLogoUrl" itemprop="url" content="https://static.bmcdn.com/lp/images/standard/bv2/share/constant-contact-share-logo.gif" />
                        <?php
                        if ($website) {
                            echo "<a class='author-website' rel='nofollow' href='" . $website . "' target='_blank'>" . $first_name . "'s Website <i class='fas fa-external-link-alt'></i></a>";
                        }
                        ?>
                    </header>
                    <p><?php echo $bio; ?></p>
                </div>
            </section>
<?php
        }
    }
endif;
