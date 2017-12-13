<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>OSSN Admin Dashboard</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="../node_modules/angular/angular.js"></script>
    <script src="js/app.js"></script>
  </head>
  <body>
      <?php 
          $handle = popen('python3 -u /var/www/html/commstatus.py', 'r');
      ?>
    <nav class="navbar navbar-inverse" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">OSSN Admin</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="#">Dashboard</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Help</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container-fluid" ng-app="dashboardApp" ng-controller="DashboardController" style="margin-top:4rem">
      <div class="row">
        <div class="col-sm-4">
          <div class="thumbnail">
            <div class="caption">
              <h4><span class="indicator running"></span> Running VMs</h4>
            </div>
            <div class="dash bg-success text-center">
              <h2>{{getServerTally().running}}</h2>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="thumbnail">
            <div class="caption">
              <h4><span class="indicator stopped"></span> Stopped VMs</h4>
            </div>
            <div class="dash bg-danger text-center">
              <h2>{{getServerTally().stopped}}</h2>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="thumbnail">
            <div class="caption">
              <h4><span class="indicator default"></span> Total VMs</h4>
            </div>
            <div class="dash bg-info text-center">
              <h2>{{getServerTally().total}}</h2>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">EC2 Instances</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-xs-6 col-md-4">
                 <label class="control-label" for="inputSuccess2">Group Actions</label><br/>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <span class="badge" ng-model="server.Selected">{{countSelected()}}</span>
                    </span>
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-default" ng-click="groupStart()"><span class="glyphicon glyphicon-play" aria-hidden="true"></span> <span class="hidden-xs">Start</span></button>
                      <button type="button" class="btn btn-default" ng-click="groupStop()"><span class="glyphicon glyphicon-stop" aria-hidden="true"></span> <span class="hidden-xs">Stop</span></button>
                      <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> <span class="hidden-xs">Restart</span></button>
                    </div>
                  </div>
                  <div class="select-all">
                    <input type="checkbox" id="tb-select-all" ng-model="selectedAll" ng-click="checkAll(); checkbox.checked = !checkbox.checked"> <label for="tb-select-all" style="font-weight:normal">{{checkbox.checked ? 'Deselect All' : 'Select All'}}</label>
                  </div>
                </div>
                <div class="col-xs-6 col-md-3">
                  <label class="control-label" for="inputSuccess2">Sort by</label>
                  <select class="form-control" ng-model="sortorder" ng-init="sortorder='+name'">
                    <option value="+name">Server Name</option>
                    <option value="+type">Server Type</option>
                    <option value="+state">Server Status</option>
                  </select>
                </div>
              </div>
            </div>
            <table class="table table-responsive server-rows">
              <thead>
                <tr>
                  <th></th>
                  <th>Server</th>
                  <th>Type</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody ng-include="getServerTemplate()"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
