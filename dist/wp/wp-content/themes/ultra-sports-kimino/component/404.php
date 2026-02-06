<?php
global $path, $siteURL, $homeURL;

if(is_404()) {
  echo <<< EOD
    <script type="text/javascript">
      location.href = "{$homeURL}";
    </script>

  EOD;
}
?>