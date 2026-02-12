<?php
  /*
  Template Name: ABOUT
  */

  global $path, $siteURL, $homeURL;
  get_header();
  ob_start();
?>

<section class="mv">
  <figure class="bg">
    <picture>
      <source srcset="<?php echo $path; ?>/assets/img/about/mv_sp.webp" media="(max-width: 759px) and (orientation: portrait)">
      <img src="<?php echo $path; ?>/assets/img/about/mv.webp" alt="" fetchpriority="high">
    </picture>
  </figure>

  <div class="txt">
    <div class="inner">
      <h2 data-target data-slideup>ULTRA SPORTS KIMINOについて</h2>
      <h1 data-target data-slideup>スポーツの先に、<br data-sp>何がある？</h1>
      <p data-target data-slideup>スポーツを、考えよう。<br>
        体から届くサインを、<br>プレーヤーから届くコンタクトを。<br><br>

        その先には何があるだろう。<br>
        ここは、誰もがスポーツを楽しむだけでなく、<br data-pc data-tb>テクノロジーの力で技術を磨いていける場所。<br data-pc data-tb>AIが動きを見つめ、気づきを与え、<br data-pc data-tb>考える力で背中を押してくれる。<br><br>

        その積み重ねが、<br>プレーを想像以上に進化させる。<br><br>

        WHAT’S BEYOND PLAY?<br>
        衝動のままに楽しむ<br data-sp>スポーツの快感とともに、<br>もっと自由に、限界の先へ。</p>
    </div>
  </div>
</section>

<div class="page_container">
  <section class="menu">
    <ul class="contents">
      <li data-target data-slideup>
        <a href="<?php echo $siteURL; ?>/sports/">
          <div class="dot_bg">
            <div class="dot_line lt"></div>
            <div class="dot_line lb"></div>
            <div class="dot_line rt"></div>
            <div class="dot_line rb"></div>
          </div>
          <dl class="txt">
            <dt>SPORTS</dt>
            <dd>
              <p>個人からチーム、様々なエリアで各種スポーツをプレーができます。個人の筋力トレーニングでのジムから、大会開催まで幅広いスポーツへの対応が可能です。フリースペースとしての機能も持ち、キッズエリアでの子供の遊びや、園路のウォーキングも対応しています。各種スポーツに合わせた機材や道具の貸し出しも行っており、ナイター利用(ＰＭ9時まで)も可能です。</p>
            </dd>
          </dl>
          <figure><img src="<?php echo $path; ?>/assets/img/about/img_sports.webp" alt=""></figure>
        </a>
      </li>

      <li data-target data-slideup>
        <a href="<?php echo $siteURL; ?>/camp-food/">
          <div class="dot_bg">
            <div class="dot_line lt"></div>
            <div class="dot_line lb"></div>
            <div class="dot_line rt"></div>
            <div class="dot_line rb"></div>
          </div>
          <dl class="txt">
            <dt>CAMP</dt>
            <dd>
              <p>個人からチームまで(最大48名)に対応する宿泊所を利用できます。12室の宿泊室はそれぞれ最大4名まで利用でき、シャワー室を完備しています。宿泊の際の食事提供や映像チェックを可能とするミーティングスペース、筋力トレーニングのためのジムを備えた宿泊エリアです。</p>
            </dd>
          </dl>
          <figure><img src="<?php echo $path; ?>/assets/img/about/img_camp.webp" alt=""></figure>
        </a>
      </li>

      <li data-target data-slideup>
        <a href="<?php echo $siteURL; ?>/camp-food/#food">
          <div class="dot_bg">
            <div class="dot_line lt"></div>
            <div class="dot_line lb"></div>
            <div class="dot_line rt"></div>
            <div class="dot_line rb"></div>
          </div>
          <dl class="txt">
            <dt>FOOD</dt>
            <dd>
              <p>合宿に対応する栄養管理した朝昼晩の食事の提供や、観客や散歩に来られた方用にカフェやスイーツ、ランチの提供も行っています。栄養士監修によるチームオファーに対応した栄養変更メニューも可能です。大会やイベント時の提供調整や各種対応も対応可です。</p>
            </dd>
          </dl>
          <figure><img src="<?php echo $path; ?>/assets/img/about/img_food.webp" alt=""></figure>
        </a>
      </li>
    </ul>
  </section>

  <section class="overview">
    <div class="white_bg"></div>

    <h2 class="sec_title" data-target data-slideup>
      <span class="en">OVERVIEW</span>
      <span class="ja">施設概要</span>
    </h2>

    <div class="contents" data-target data-slideup>
      <dl>
        <dt>施設名</dt>
        <dd>ULTRA SPORTS KIMINO</dd>
      </dl>

      <dl>
        <dt>運営会社</dt>
        <dd>Kimino Studies株式会社</dd>
      </dl>

      <dl>
        <dt>所在地 </dt>
        <dd>和歌山県海草郡紀美野町動木518</dd>
      </dl>

      <dl>
        <dt>施設営業時間</dt>
        <dd>9:00 - 21:00<br>火曜定休</dd>
      </dl>

      <dl>
        <dt>宿泊施設営業時間</dt>
        <dd>
          チェックイン　　15:00 〜 18:00<br>
          チェックアウト　10:00
        </dd>
      </dl>
    </div>
  </section>
</div>

<?php $content = ob_get_clean(); echo preg_replace('/^/m', str_repeat(' ', 8), $content); ?>
<?php get_footer(); ?>