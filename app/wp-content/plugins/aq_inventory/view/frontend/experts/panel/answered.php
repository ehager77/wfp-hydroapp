<?php if ($info['result']) : ?>

    <?php if (!empty($info['chats']['answered'])) : ?>

        <?php foreach ($info['chats']['answered'] as $chat) : ?>
            <div class="aqi-chats-chatbox">
                <div class="aqi-chats-username">
                    <h3>
                        <a href="?question=<?= $chat['id'] ?>">
                            <i class="fa fa-check" aria-hidden="true"></i>

                            <span><?= esc_html($chat['title']) ?></span>
                        </a>
                    </h3>
                </div>
                <a href="?question=<?= $chat['id'] ?>">
                    <div class="aqi-chats-msg">
                        <?= esc_html($chat['last_msg']) ?>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>

    <?php else : ?>
        <?php aqi_notification('commenting', '#00b19e', pll__('There aren\'t questions has been answered')) ?>
    <?php endif; ?>


<?php else : ?>
    <?php aqi_error_msg(pll__('Error'), $info['error']) ?>
<?php endif; ?>
