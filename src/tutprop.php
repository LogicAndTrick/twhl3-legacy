<?
if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to do this.","index.php");
?>
			<div class="single-center">
				<h1>Propose a Tutorial</h1>
				<h3>Proposing a Tutorial</h3>
				<p class="single-center-content">
					Welcome to the TWHL Tutorial Proposal form! From here, you'll be able to propose a tutorial that you wish to contribute to the site. To do this, simply fill in all the fields on the form below. Here are some guidelines to get you started:
				</p>
				<ul>
					<li>This is not the place to simply post a completed tutorial. If you do decide to post a complete tutorial here, it will be chucked into the TWHL incinerator.</li>
					<li>Check to make sure that your tutorial idea has not already been covered by a published tutorial.</li>
					<li>Make sure your intentions behind the tutorial are clear and concise.</li>
				</ul>
				<p class="single-center-content">
					Follow this, and you may just be the next member to submit a tutorial to TWHL! If you need help using this form, please contact one of the <a href="contact.php">tutorial moderators</a>.
				</p>	
			</div>				
			<div class="single-center" id="gap-fix">
				<h1>Proposal Form</h1>
				<form action="tutaddprop.php" method="post">
					<h3>Step One</h3>
					<fieldset class="new-thread">
						<p class="single-center-content">
							<select class="right" name="engine">
								<option value="1">My tutorial is for the Goldsource engine</option>
								<option value="2">My tutorial is for the Source engine</option>
								<option value="4">My tutorial does not fit into these categories&nbsp;&nbsp;</option>
							</select>
							1. Which engine is your tutorial for?<br/>
							<select class="right" name="difficulty">
								<option value="0">My tutorial will be for beginners</option>
								<option value="1">My tutorial will be for intermediate users&nbsp;&nbsp;</option>
								<option value="2">My tutorial will be for advanced users</option>
							</select>
							2. What difficulty level will your tutorial be at?<br/>
							<input class="right" name="keywords" type="text" size="20" /> 3.Please enter some keywords to describe your tutorial.<br />
							<input class="right" name="title" type="text" size="20" />4. Please enter the name of your tutorial.
						</p>
					</fieldset>
					<h3>Step Two</h3>
					<fieldset class="new-thread">
						<p class="single-center-content">
							5. Please give us an outline of your proposed tutorial.<br />
							<textarea name="details" rows="10" cols="76"></textarea> <br />
							<input class="right" name="notes" type="text" size="20" />6. Are there any other notes/messages that you want to add?
						</p>
					</fieldset>
					<h3>Step Three</h3>
					<fieldset class="new-thread">
						<p class="single-center-content">
							<input class="right" type="submit" value="Submit" />7. Submit your proposal!
						</p>
					</fieldset>
				</form>
			</div>
