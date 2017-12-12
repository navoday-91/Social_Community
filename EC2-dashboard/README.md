<h1>EC2-dashboard<h1>
<p>A simple UI to display and interact with EC2 Instances</p>
<h3>How to Launch App</h3>
<ul>
<li>Requires a server to run</li>
<li>Add packaged files to server directory</li>
<li>Point browser URL to <localhost>/ec2-dashboard/app/index.html</li>
</ul>
<h3>General Notes</h3>
<ul>
<li>App developed and tested in MAMP server environment, running Google Chrome version 39.0.2171.65</li>
<li>Data for the app is contained within static JSON file located in ec2-dashboard/app/data/servers.json</li>
<li>App uses Angular.js, LESS, and Bootstrap as dependencies</li>
<li>Main controller can be found in ec2-dashboard/app/js/app.js</li>
</ul>
<h3>How It Works</h3>
<ul>
<li>Each server instance can be run or stopped indivually by clicking server actions within each server row</li>
<li>Multiple servers can be run or stopped by using the Group Actions interface above the server list</li>
<li>Servers can be sorted by server name, server type, and server status</li>
<li>Although designed in a responsive framework, mobile devices should be used in landscape mode</li>
</ul>
