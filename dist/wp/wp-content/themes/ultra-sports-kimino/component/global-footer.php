<?php
  global $path, $siteURL, $homeURL;
?>

<footer id="globalFooter">
  <a class="logo" href="<?php echo $siteURL; ?>" aria-label="ULTRA SPORTS KIMINO">
    <i class="l_icon" data-sp></i>
    <i class="l_text"></i>
  </a>

  <div class="inner">
    <div class="main">
      <div class="access">
        <div class="title">ACCESS</div>
        <div class="txt">
          <p>〒640-1131 和歌山県海草郡紀美野町動木518</p>
          <dl>
            <dt>電話番号</dt>
            <dd>073-488-2082</dd>
          </dl>
          <dl>
            <dt>営業時間</dt>
            <dd>9:00-21:00</dd>
          </dl>
          <dl>
            <dt>定休日</dt>
            <dd>火曜日</dd>
          </dl>
        </div>
      </div>

      <div class="link_unit">
        <ul class="page">
          <li>
            <a href="<?php echo $siteURL; ?>/contact/">
              <span class="en">CONTACT</span>
              <span class="ja">お問い合わせ</span>
            </a>
          </li>

          <li>
            <a href="<?php echo $siteURL; ?>/faq/">
              <span class="en">FAQ</span>
              <span class="ja">よくあるご質問</span>
            </a>
          </li>
        </ul>

        <?php echo get_component_with_indent('component/sns', 6); ?>

      </div>
    </div>

    <p class="copyright">© ultrasportskimino.jp</p>
  </div>
</footer>