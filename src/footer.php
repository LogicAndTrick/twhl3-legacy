<div class="clearfix"></div>
</div>
<div class="footer">
<?
/*
<!--

=== Ode to February 29th ===
[A cheesy poem by Penguinboy]

It doesn't come too often,
And it's really not that great;
But every four years or so,
It comes just after twenty-eight.

It makes the summer longer
(That's if you live down south),
And just that single fact alone
Leaves a bad taste in my mouth.

Screw the summer equinox;
Such a stupid choice of date.
The proper meteorological definition
ends on February twenty-eight.
Though every four years that changes,
Through a harsh, cruel twist of fate
Something happens to the month-
Something that I hate.
The length of February grows
at a very alarming rate.
One full day the year is extended
To increase the Olympic wait.

Though something happens on that day
That one man awaits with glee-
It sends him to the moon and back;
Then to the bottom of the sea.
He anticipates it more than a Brit
Longing for that morning cup of tea.
(But does that make it worth it?
I think we can all agree
That it does not at all improve the fact that
It's worse than HTML's <marquee>!)

But let's return to this one guy
(to get things back on track)
He escaped from us a few years ago
And I don't think he'll be back.
Though every year his spirit returns
Wielding a naughty little hack.
He appears out of nowhere - unseen-
Shrouded in a cloak of black;
What trick does he have this year?
What is his plan of attack?

Whether it be a picture of Wall-E
Or a bunch of other Pixar folk,
He can always be counted on each year
To provide us with a little joke!
He does not forget! Every year without fail!
He is quite reliable - solid as an oak.
This time of year, his very deepest thoughts
Of pride and honour does provoke;
For on this day of days, the twenty-ninth
He is the world's greatest bloke!

So what's so great about today
What's the point of all these tricks?
Well, today's the day; After four long years-
He's finally getting his fix!
A single tear of joy rolls down his face
As he blows out those lighted wicks-
Remember folks, Ant loves you all!
And today he's turning six!

-->
    $ant_tubes = array(
        'http://www.youtube.com/embed/Cyk_d_1VSas',
        'http://www.youtube.com/embed/tc1ilmImfRc',
        'http://www.youtube.com/embed/7C8GWIV4jzA',
        'http://www.youtube.com/embed/-u0RQkj3Tic',
        'http://www.youtube.com/embed/GqOC_knfvqw',
        'http://www.youtube.com/embed/bYdGVrUhyBM',
        'http://www.youtube.com/embed/BUKOebCbINc',
        'http://www.youtube.com/embed/cR1Q0MvAAZg',
        'http://www.youtube.com/embed/6X3TE8dKdrA',
        'http://www.youtube.com/embed/lyHSjv9gxlE',
        'http://www.youtube.com/embed/WAE5z8Ct56g',
        'http://www.youtube.com/embed/SdpxG4Y6z8s'
    );
    $tube = $ant_tubes[array_rand($ant_tubes)];
    echo '<iframe id="Ant_Turns_Six" width="800" height="480" src="' . $tube . '?rel=0" frameborder="0" allowfullscreen></iframe><br>';
*/
?>
Site and non-dynamic content copyright ©2008, Ant, Strider and Penguinboy. Original site by Atom. All rights reserved.<br />
All member-submitted resources copyright their respective authors unless otherwise specified.<br />
<a href="contact.php">Contact us</a><br />
This version: TWHL3 v3077 - Site updated 21st March, 2008.<br />
<?
if (isset($timer)) {
	$exectime = round(((time() + microtime()) - $timer)*1000);
	switch($exectime) {
		case $exectime < 50:
			$execdesc = "a blindingly fast";break;
		case $exectime < 100:
			$execdesc = "a speedy";break;
		case $exectime < 200:
			$execdesc = "a pretty quick";break;
		case $exectime < 400:
			$execdesc = "a decent";break;
		case $exectime < 800:
			$execdesc = "a plodding";break;
		case $exectime < 1600:
			$execdesc = "a slow";break;
		case $exectime < 3200:
			$execdesc = "a lousy";break;
		case $exectime >= 6400:
			$execdesc = "a dire";break;
	}
	echo "Processed in $execdesc $exectime milliseconds";
}
?>
		</div>
	</body>
</html>