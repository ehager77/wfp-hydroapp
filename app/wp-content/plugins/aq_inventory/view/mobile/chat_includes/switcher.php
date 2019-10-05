<?php if (isset($_GET['from']) && $_GET['from'] === 'chats') : ?>

    <div class="pnSwitcherHuge pnSwitcherHuge_shadow">
        <a href="?chats">
            <div class="pnSwitcherHuge__button pnSwitcherHuge__button_single">
                <i class="fa fa-arrow-left fa_margin" aria-hidden="true"></i>
                <?php pll_e('Messages') ?>
            </div>
        </a>
    </div>

<?php else : ?>

    <div class="pnSwitcherHuge pnSwitcherHuge_shadow">
        <a href="?id_user=<?= $_GET['chat'] ?>">
            <div class="pnSwitcherHuge__button pnSwitcherHuge__button_single">
                <i class="fa fa-arrow-left fa_margin" aria-hidden="true"></i>
                <?php pll_e('Back to user') ?>
            </div>
        </a>
    </div>

<?php endif; ?>