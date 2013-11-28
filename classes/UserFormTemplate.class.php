<?php
class UserFormTemplate {
	public function html(IUserAccount $user) {
		$html = '
<div id="u" class="u">
	<input type="hidden" name="id" id="id" value="' . $user->getUserId() . '"/>
	<label>Email: <input type="text" name="email" id="email" value="' . $user->getEmail() . '"/></label>
	<label>First name: <input type="text" name="fn" id="fn" value="' . $user->getFirstName() . '"/></label>
	<label>Last name: <input type="text" name="ln" id="ln" value="' . $user->getLastName() . '"/></label>
	<button type="button" onclick="main.u.save();">Save</button>
	<button type="button" onclick="main.cancel();">Cancel</button>
</div>';

		return $html;
	}
}
?>
