<?php
  /*
  Template Name: CONTACT
  */

  global $path, $siteURL, $homeURL;
  get_header();
  ob_start();
?>

<header class="page_header">
  <h1 class="page_title">
    <span class="en">CONTACT</span>
    <span class="ja">お問い合わせ</span>
  </h1>
</header>

<div class="page_container">
  <section class="contact-form" id="formContents">
    <div class="form-section" id="form-area">
      <form id="contact-form" method="post" action="<?php echo $path; ?>/contactform/" novalidate data-target data-slideup>
        <div class="form-container">
          <div class="form-column">
            <div class="form-group">
              <dl class="form-unit">
                <dt>
                  <label for="name">
                    お名前
                    <span class="required">※必須</span>
                  </label>
                </dt>
                <dd>
                  <input class="half" id="name" type="text" name="name" placeholder="例）山田太郎" required>
                </dd>
              </dl>
              <div class="error-message" data-error="name"></div>
            </div>

            <div class="form-group">
              <dl class="form-unit">
                <dt>
                  <label for="tel">電話番号</label>
                </dt>
                <dd>
                  <input id="tel" type="tel" name="tel" placeholder="例）090-0000-0000">
                </dd>
              </dl>
              <div class="error-message" data-error="tel"></div>
            </div>
          </div>

          <div class="form-column">
            <div class="form-group">
              <dl class="form-unit">
                <dt>
                  <label for="email">
                    メールアドレス
                    <span class="required">※必須</span>
                  </label>
                </dt>
                <dd>
                  <input id="email" type="email" name="email" placeholder="例）info@ultrasportskimino.jp" required>
                </dd>
              </dl>
              <div class="error-message" data-error="email"></div>
            </div>

            <div class="form-group">
              <dl class="form-unit">
                <dt>
                  <label for="email2">
                    メールアドレス（確認用）
                    <span class="required">※必須</span>
                  </label>
                </dt>
                <dd>
                  <input id="email2" type="email" name="email2" placeholder="例）info@ultrasportskimino.jp" required>
                </dd>
              </dl>
              <div class="error-message" data-error="email2"></div>
            </div>
          </div>

          <div class="form-group">
            <dl class="form-unit">
              <dt>
                <label for="message">
                  お問合せ内容
                  <span class="required">必須</span>
                </label>
              </dt>
              <dd>
                <textarea id="message" name="message" rows="7" placeholder="お問合せ内容をご記入ください。" required></textarea>
              </dd>
            </dl>
            <div class="error-message" data-error="message"></div>
          </div>
        </div>

        <div class="form-footer">
          <p>こちらから一度お電話またはメールにてご連絡差し上げます。<br><br>
            お問い合わせ内容を確認でき次第、担当者より折り返しのご連絡を差し上げます。メールアドレスの誤入力、携帯電話のドメイン指定受信設定により確認メールを受診できない場合がございます。迷惑メールなどに入っている場合もございますので、再度受信設定をご確認ください。当社からの返信がない場合は、お手数ですが、再度お問い合わせいただきますようお願い致します。</p>

          <div class="form-buttons">
            <button class="btn btn_submit disable" type="submit" disabled>確認画面へ</button>
          </div>
        </div>
      </form>
    </div>

    <div class="form-section hide" id="confirm-area"></div>

    <div class="form-section hide" id="complete-area"></div>

    <div class="hide" id="formLoading">
      <div class="spinner">送信中...</div>
    </div>
  </section>
</div>


<?php $content = ob_get_clean(); echo preg_replace('/^/m', str_repeat(' ', 8), $content); ?>
<?php get_footer(); ?>