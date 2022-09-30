<?php if($text_column_position == 'Bottom'): ?>
    <div class="grid container container-cs">

        <div class="gr_1_2-1 testimonial-col" style="display: flex; align-items: center;">
            <div>
                <?php render_svg('quote.svg', 'testimonial__quote-highlight__icon'); ?>
                <?php if(!empty($quote_highlight)): ?>
                    <h2 class="testimonial__quote-highlight__wide flex col afs">
                        <?php echo $quote_highlight; ?>
                    </h2>
                <?php endif; ?>
            </div>
        </div>
    
        <div class="gr_1_2-2 testimonial-col" style="display: flex; align-items: center;">
            <div>
                <?php if(!empty($long_quote)): ?>
                    <p class="testimonial__long-quote"><?php echo $long_quote; ?></p>
                <?php endif; ?>
                <div class="testimonial__credit flex row afc">
                    <?php if(!empty($author)): ?>
                        <p class="testimonial__credit__author">–– <?php echo $author; ?></p>
                    <?php endif; ?>
                    <?php if(!empty($company)): ?>
                        <p class="testimonial__credit__company">, <?php echo $company; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

<?php else: ?>
    <div class="<?php echo $text_col_class; ?> row jfs testimonial__short-side" style="display: flex; align-items: center;">
        <div>
            <?php render_svg('quote.svg', 'testimonial__quote-highlight__icon'); ?>
            <?php if(!empty($quote_highlight)): ?>
                <h3 class="testimonial__quote-highlight flex col afs">
                    <?php echo $quote_highlight; ?>
                </h3>
            <?php endif; ?>
            <?php if(!empty($long_quote)): ?>
                <p class="testimonial__long-quote"><?php echo $long_quote; ?></p>
            <?php endif; ?>
            <div class="testimonial__credit flex row afc">
                <?php if(!empty($author)): ?>
                    <p class="testimonial__credit__author">–– <?php echo $author; ?></p>
                <?php endif; ?>
                <?php if(!empty($company)): ?>
                    <p class="testimonial__credit__company">, <?php echo $company; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>