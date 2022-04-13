<?php

// Include index.php so translation has been read (or not if not yet translated)
require_once "app/index.php";

use Diversen\Lang;

?>
<!-- simple translastion of a string -->
<p><?=Lang::translate('Here is a test');?></p>


<p>
<!-- with substitution and a span to indicate that a part of a string should not be translated -->
<!-- the <span class="notranslate"></span> indicates that google should ignore translating the part in the <span>-->
<p>
    <?=Lang::translate('User with ID <span class="notranslate">{ID}</span> has been locked!', array ('ID' => 42))?>
</p>


<!-- with substitution and a span to indicate that a part of a string should not be translated -->
<p>
    <?=Lang::translate('User with ID <span class="notranslate">{ID}</span> and username <span class="notranslate">{username}</span> has been locked !', array ('ID' => 72, 'username' => 'Dennis'))?>
</p>
