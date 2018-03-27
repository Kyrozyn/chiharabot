<?php
function adminer_object() {
  
  class AdminerSoftware extends Adminer {
    
	function loginForm() {
    ?>
<table cellspacing="0">
<tr><th><?php echo lang('Username'); ?><td><input type="hidden" name="driver" value="pgsql"><input type="hidden" name="server" value="localhost"><input id="username" name="username" value="<?php echo h($_GET["username"]);  ?>">
<tr><th><?php echo lang('Password'); ?><td><input type="password" name="password">
</table>
<?php
    echo "<p><input type='submit' value='" . lang('Login') . "'>\n";
    echo checkbox("permanent", 1, $_COOKIE["adminer_permanent"], lang('Permanent login')) . "\n";
}

    function name() {
      // custom name in title and heading
      return 'Ky';
    }
    
    function permanentLogin() {
      // key used for permanent login
      return 'c77f3ed6d9ff26ca2986d816dcf1d7be';
    }
    
    function credentials() {
      // server, username and password for connecting to database
      return array('ec2-54-235-165-114.compute-1.amazonaws.com', 'mwbyxqqvoryaof', '695edb1c23d62c98e46d1331919340652a3832dd73c01357cf17122d1bf74dce');
    }
    
    function database() {
      // database name, will be escaped by Adminer
      return 'd4b69g3c3g5f82';
    }
    
    function login($login, $password) {
      // validate user submitted credentials
      return ($login == 'admin' && $password == 'ngokangloduck');
    }
    
    /*function tableName($tableStatus) {
      // tables without comments would return empty string and will be ignored by Adminer
      return h($tableStatus['Comment']);
    }
    
    function fieldName($field, $order = 0) {
      // only columns with comments will be displayed and only the first five in select
      return ($order <= 5 && !preg_match('~_(md5|sha1)$~', $field['field']) ? h($field['comment']) : '');
    }
    */
  }
  
  return new AdminerSoftware;
}

include './editor.php';