<div class="confirm-content" data-target data-slideup>
  <!-- <h2>入力内容の確認</h2>
  <p>以下の内容で送信します。よろしければ「SEND」ボタンを押してください。</p> -->

  <div class="confirm-table">
    <dl class="form-group confirm">
      <dt>お名前</dt>
      <dd><?= $name ?></dd>
    </dl>

    <dl class="form-group confirm">
      <dt>電話番号</dt>
      <dd><?= $tel ?></dd>
    </dl>

    <dl class="form-group confirm">
      <dt>メールアドレス</dt>
      <dd><?= $email ?></dd>
    </dl>

    <dl class="form-group confirm">
      <dt>お問合せ内容</dt>
      <dd><?= $message ?></dd>
    </dl>
  </div>

  <div class="form-buttons">
    <button class="btn back" type="button">戻る</button>
    <button class="btn send" type="button">送信する</button>
  </div>
</div>