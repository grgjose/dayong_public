    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- Info Boxes Summary -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Active Agents</span>
                <span class="info-box-number">{{ $active_agents; }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Collections (Today)</span>
                <span class="info-box-number">{{ $collections_today; }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-search-dollar"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">New Sales (Today)</span>
                <span class="info-box-number">{{ $members_today; }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-coins"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Profit (Today)</span>
                <span class="info-box-number">{{ $profit_today; }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>

        <!-- Monthly Cap Report Data -->
        <script>
          var salesChartData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 
                     'August', 'September', 'October', 'November', 'December'],
            datasets: [
              {
                label: 'Collection',
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: false,
                pointColor: 'rgba(60,141,188,0.8)',
                pointStrokeColor: 'rgba(60,141,188,0.8)',
                pointHighlightFill: 'rgba(60,141,188,0.8)',
                pointHighlightStroke: 'rgba(60,141,188,0.8)',
                data: [28, 48, 40, 19, 86, 27, 90, 95, 100, 20, 30, 42]
              },
              {
                label: 'New Members (Sales)',
                backgroundColor: 'rgba(217, 83, 79, 1)',
                borderColor: 'rgba(217, 83, 79, 1)',
                pointRadius: false,
                pointColor: 'rgba(217, 83, 79, 1)',
                pointStrokeColor: 'rgba(217, 83, 79, 1)',
                pointHighlightFill: 'rgba(217, 83, 79, 1)',
                pointHighlightStroke: 'rgba(217, 83, 79, 1)',
                data: [65, 59, 80, 81, 56, 55, 40, 12, 16, 61, 42, 42]
              },
              {
                label: 'Reactivated',
                backgroundColor: 'rgba(92, 184, 92, 1)',
                borderColor: 'rgba(92, 184, 92, 1)',
                pointRadius: false,
                pointColor: 'rgba(92, 184, 92, 1)',
                pointStrokeColor: 'rgba(92, 184, 92, 1)',
                pointHighlightFill: 'rgba(92, 184, 92, 1)',
                pointHighlightStroke: 'rgba(92, 184, 92, 1)',
                data: [65, 59, 80, 100, 100, 100, 100, 80, 81, 56, 55, 42]
              },
              {
                label: 'Transferred',
                backgroundColor: 'rgba(240, 173, 78, 1)',
                borderColor: 'rgba(240, 173, 78, 1)',
                pointRadius: false,
                pointColor: 'rgba(240, 173, 78, 1)',
                pointStrokeColor: 'rgba(240, 173, 78, 1)',
                pointHighlightFill: 'rgba(240, 173, 78, 1)',
                pointHighlightStroke: 'rgba(240, 173, 78, 1)',
                data: [100, 95, 80, 81, 56, 55, 40, 80, 81, 56, 55, 100]
              }
            ]
          }
        </script>

        <!-- Monthly Cap Report -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Monthly Recap Report</h5>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <!-- To be Edited if action required based on result in Dashboard
                    <div class="btn-group">
                      <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-wrench"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right" role="menu">
                        <a href="#" class="dropdown-item">Action</a>
                        <a href="#" class="dropdown-item">Another action</a>
                        <a href="#" class="dropdown-item">Something else here</a>
                        <a class="dropdown-divider"></a>
                        <a href="#" class="dropdown-item">Separated link</a>
                      </div>
                      
                    </div>
                  -->
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>

              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                    <p class="text-center">
                      <strong>Sales: Month of {{ date('F, Y'); }}</strong>
                    </p>

                    <div class="chart">
                      <!-- Sales Chart Canvas -->
                      <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
                    </div>
                    <!-- /.chart-responsive -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-4">
                    <p class="text-center"> <strong>Goal Completion</strong> </p>

                    <div class="progress-group">
                      Collection 
                      <span class="float-right"><b>160</b>/ 100,000</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" style="width: {{ (160/100000)*100 }}%"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->

                    <div class="progress-group">
                      New Members
                      <span class="float-right"><b>310</b>/400</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-danger" style="width: {{ (310/400)*100 }}%"></div>
                      </div>
                    </div>

                    <!-- /.progress-group -->
                    <div class="progress-group">
                      Reactivated
                      <span class="float-right"><b>480</b>/800</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-success" style="width: {{ (480/800)*100 }}%"></div>
                      </div>
                    </div>

                    <!-- /.progress-group -->
                    <div class="progress-group">
                      Transferred
                      <span class="float-right"><b>250</b>/500</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-warning" style="width: {{ (250/500)*100 }}%"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>

              <!-- ./card-body -->
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>
                      <h5 class="description-header">₱{{ $total_col_this_month; }}</h5>
                      <span class="description-text">TOTAL COLLECTION</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
                      <h5 class="description-header">₱100,390.90</h5>
                      <span class="description-text">TOTAL COST</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                      <h5 class="description-header">₱924,813.53</h5>
                      <span class="description-text">TOTAL PROFIT</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block">
                      <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
                      <h5 class="description-header">₱1,200,000.00</h5>
                      <span class="description-text">GOAL COMPLETIONS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>

        <div class="row">

          <div class="col-md-8">
            <!-- MAP & BOX PANE -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Map Monitoring</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="d-md-flex">
                  <div class="p-1 flex-fill" style="overflow: hidden">
                    <!-- Map will be created here
                    <div id="container3" style="height: 325px; overflow: hidden">
                      <div class="map"></div>
                    </div> -->
                    <div id="container3"></div>
                  </div>
                
                  <!--
                  <div class="card-pane-right bg-success pt-2 pb-2 pl-4 pr-4">
                 
                    <div class="description-block mb-4">
                      <div class="sparkbar pad" data-color="#fff">90,70,90,70,75,80,70</div>
                      <h5 class="description-header">8390</h5>
                      <span class="description-text">Visits</span>
                    </div>
                    
                    <div class="description-block mb-4">
                      <div class="sparkbar pad" data-color="#fff">90,50,90,70,61,83,63</div>
                      <h5 class="description-header">30%</h5>
                      <span class="description-text">Referrals</span>
                    </div>
                    
                    <div class="description-block">
                      <div class="sparkbar pad" data-color="#fff">90,50,90,70,61,83,63</div>
                      <h5 class="description-header">70%</h5>
                      <span class="description-text">New Sales</span>
                    </div>
                
                  </div> 
                  -->
                </div><!-- /.d-md-flex -->
              </div>
              <!-- /.card-body -->
            </div>


            <div class="row">

              <!-- Commented Out Chat
                <div class="col-md-6">
                  <div class="card direct-chat direct-chat-warning">
                    <div class="card-header">
                      <h3 class="card-title">Direct Chat</h3>

                      <div class="card-tools">
                        <span title="3 New Messages" class="badge badge-warning">3</span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                          <i class="fas fa-comments"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                    </div>
                
                    <div class="card-body">
                      
                      <div class="direct-chat-messages">
                      
                        <div class="direct-chat-msg">
                          <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-left">Alexander Pierce</span>
                            <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                          </div>
                          
                          <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                          
                          <div class="direct-chat-text">
                            Is this template really for free? That's unbelievable!
                          </div>
                        
                        </div>
                    
                        <div class="direct-chat-msg right">
                          <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-right">Sarah Bullock</span>
                            <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                          </div>
                      
                          <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
      
                          <div class="direct-chat-text">
                            You better believe it!
                          </div>
      
                        </div>
          
                        <div class="direct-chat-msg">
                          <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-left">Alexander Pierce</span>
                            <span class="direct-chat-timestamp float-right">23 Jan 5:37 pm</span>
                          </div>
        
                          <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">

                          <div class="direct-chat-text">
                            Working with AdminLTE on a great new app! Wanna join?
                          </div>

                        </div>

                        <div class="direct-chat-msg right">
                          <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-right">Sarah Bullock</span>
                            <span class="direct-chat-timestamp float-left">23 Jan 6:10 pm</span>
                          </div>

                          <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">

                          <div class="direct-chat-text">
                            I would love to.
                          </div>

                        </div>

                      </div>



                      <div class="direct-chat-contacts">
                        <ul class="contacts-list">
                          <li>
                            <a href="#">
                              <img class="contacts-list-img" src="dist/img/user1-128x128.jpg" alt="User Avatar">

                              <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Count Dracula
                                  <small class="contacts-list-date float-right">2/28/2015</small>
                                </span>
                                <span class="contacts-list-msg">How have you been? I was...</span>
                              </div>

                            </a>
                          </li>

                          <li>
                            <a href="#">
                              <img class="contacts-list-img" src="dist/img/user7-128x128.jpg" alt="User Avatar">

                              <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Sarah Doe
                                  <small class="contacts-list-date float-right">2/23/2015</small>
                                </span>
                                <span class="contacts-list-msg">I will be waiting for...</span>
                              </div>
  
                            </a>
                          </li>
              
                          <li>
                            <a href="#">
                              <img class="contacts-list-img" src="dist/img/user3-128x128.jpg" alt="User Avatar">

                              <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Nadia Jolie
                                  <small class="contacts-list-date float-right">2/20/2015</small>
                                </span>
                                <span class="contacts-list-msg">I'll call you back at...</span>
                              </div>
            
                            </a>
                          </li>
    
                          <li>
                            <a href="#">
                              <img class="contacts-list-img" src="dist/img/user5-128x128.jpg" alt="User Avatar">

                              <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Nora S. Vans
                                  <small class="contacts-list-date float-right">2/10/2015</small>
                                </span>
                                <span class="contacts-list-msg">Where is your new...</span>
                              </div>
                
                            </a>
                          </li>
      
                          <li>
                            <a href="#">
                              <img class="contacts-list-img" src="dist/img/user6-128x128.jpg" alt="User Avatar">

                              <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  John K.
                                  <small class="contacts-list-date float-right">1/27/2015</small>
                                </span>
                                <span class="contacts-list-msg">Can I take a look at...</span>
                              </div>
                
                            </a>
                          </li>
              
                          <li>
                            <a href="#">
                              <img class="contacts-list-img" src="dist/img/user8-128x128.jpg" alt="User Avatar">

                              <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Kenneth M.
                                  <small class="contacts-list-date float-right">1/4/2015</small>
                                </span>
                                <span class="contacts-list-msg">Never mind I found...</span>
                              </div>
                    
                            </a>
                          </li>
            
                        </ul>
            
                      </div>

                    </div>
      
                    <div class="card-footer">
                      <form action="#" method="post">
                        <div class="input-group">
                          <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                          <span class="input-group-append">
                            <button type="button" class="btn btn-warning">Send</button>
                          </span>
                        </div>
                      </form>
                    </div>

                  </div>
                </div>
              -->
              <!-- USERS LIST 
              <div class="col-md-6">
                
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Latest Members</h3>

                    <div class="card-tools">
                      <span class="badge badge-danger">8 New Members</span>
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>

                  <div class="card-body p-0">
                    <ul class="users-list clearfix">
                      <li>
                        <img src="{{asset('admin_lte/dist/img/user1-128x128.jpg')}}" alt="User Image">
                        <a class="users-list-name" href="#">Alexander Pierce</a>
                        <span class="users-list-date">Today</span>
                      </li>
                      <li>
                        <img src="{{asset('admin_lte/dist/img/user8-128x128.jpg')}}" alt="User Image">
                        <a class="users-list-name" href="#">Norman</a>
                        <span class="users-list-date">Yesterday</span>
                      </li>
                      <li>
                        <img src="{{asset('admin_lte/dist/img/user7-128x128.jpg')}}" alt="User Image">
                        <a class="users-list-name" href="#">Jane</a>
                        <span class="users-list-date">12 Jan</span>
                      </li>
                      <li>
                        <img src="{{asset('admin_lte/dist/img/user6-128x128.jpg')}}" alt="User Image">
                        <a class="users-list-name" href="#">John</a>
                        <span class="users-list-date">12 Jan</span>
                      </li>
                      <li>
                        <img src="{{asset('admin_lte/dist/img/user2-160x160.jpg')}}" alt="User Image">
                        <a class="users-list-name" href="#">Alexander</a>
                        <span class="users-list-date">13 Jan</span>
                      </li>
                      <li>
                        <img src="{{asset('admin_lte/dist/img/user5-128x128.jpg')}}" alt="User Image">
                        <a class="users-list-name" href="#">Sarah</a>
                        <span class="users-list-date">14 Jan</span>
                      </li>
                      <li>
                        <img src="{{asset('admin_lte/dist/img/user4-128x128.jpg')}}" alt="User Image">
                        <a class="users-list-name" href="#">Nora</a>
                        <span class="users-list-date">15 Jan</span>
                      </li>
                      <li>
                        <img src="{{asset('admin_lte/dist/img/user3-128x128.jpg')}}" alt="User Image">
                        <a class="users-list-name" href="#">Nadia</a>
                        <span class="users-list-date">15 Jan</span>
                      </li>
                    </ul>

                  </div>

                  <div class="card-footer text-center">
                    <a href="javascript:">View All Users</a>
                  </div>

                </div>

              </div>-->

            </div>


            <!-- TABLE: LATEST ORDERS -->
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">Latest Collection</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <th>OR Number</th>
                      <th>Dayong Program</th>
                      <th>Status</th>
                      <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><a href="pages/examples/invoice.html">OR9842</a></td>
                      <td>D-999</td>
                      <td><span class="badge badge-success">Remitted</span></td>
                      <td>
                        <div class="sparkbar" data-color="#00a65a" data-height="20">₱120.00</div>
                      </td>
                    </tr>
                    <tr>
                      <td><a href="pages/examples/invoice.html">OR1848</a></td>
                      <td>D-280</td>
                      <td><span class="badge badge-warning">Pending</span></td>
                      <td>
                        <div class="sparkbar" data-color="#f39c12" data-height="20">₱120.00</div>
                      </td>
                    </tr>
                    <tr>
                      <td><a href="pages/examples/invoice.html">OR7429</a></td>
                      <td>D-380</td>
                      <td><span class="badge badge-danger">Lost</span></td>
                      <td>
                        <div class="sparkbar" data-color="#f56954" data-height="20">₱120.00</div>
                      </td>
                    </tr>
                    <tr>
                      <td><a href="pages/examples/invoice.html">OR7429</a></td>
                      <td>D-280</td>
                      <td><span class="badge badge-info">Processing</span></td>
                      <td>
                        <div class="sparkbar" data-color="#00c0ef" data-height="20">₱120.00</div>
                      </td>
                    </tr>
                    <tr>
                      <td><a href="pages/examples/invoice.html">OR1848</a></td>
                      <td>D-280</td>
                      <td><span class="badge badge-warning">Pending</span></td>
                      <td>
                        <div class="sparkbar" data-color="#f39c12" data-height="20">₱120.00</div>
                      </td>
                    </tr>
                    <tr>
                      <td><a href="pages/examples/invoice.html">OR7429</a></td>
                      <td>D-380</td>
                      <td><span class="badge badge-danger">Lost</span></td>
                      <td>
                        <div class="sparkbar" data-color="#f56954" data-height="20">₱120.00</div>
                      </td>
                    </tr>
                    <tr>
                      <td><a href="pages/examples/invoice.html">OR9842</a></td>
                      <td>D-999</td>
                      <td><span class="badge badge-success">Remitted</span></td>
                      <td>
                        <div class="sparkbar" data-color="#00a65a" data-height="20">₱120.00</div>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Orders</a>
              </div>
              <!-- /.card-footer -->
            </div>

          </div>


          <div class="col-md-4">

            <div class="info-box mb-3 bg-warning">
              <span class="info-box-icon"><i class="fas fa-tag"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">New Sales</span>
                <span class="info-box-number">5,200</span>
              </div>
              <!-- /.info-box-content -->
            </div>

            <div class="info-box mb-3 bg-success">
              <span class="info-box-icon"><i class="far fa-heart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Collections</span>
                <span class="info-box-number">92,050</span>
              </div>
              <!-- /.info-box-content -->
            </div>

            <div class="info-box mb-3 bg-danger">
              <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Collectors</span>
                <span class="info-box-number">114,381</span>
              </div>
              <!-- /.info-box-content -->
            </div>

            <div class="info-box mb-3 bg-info">
              <span class="info-box-icon"><i class="far fa-comment"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Direct Messages</span>
                <span class="info-box-number">163,921</span>
              </div>

            </div>

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Profit Report Monitoring</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
       
              <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                    <div class="chart-responsive">
                      <canvas id="pieChart" height="150"></canvas>
                    </div>

                  </div>
       
                  <div class="col-md-4">
                    <ul class="chart-legend clearfix">
                      <li><i class="far fa-circle text-danger"></i> Agdao</li>
                      <li><i class="far fa-circle text-success"></i> Tibungco</li>
                      <li><i class="far fa-circle text-warning"></i> Buhangin</li>
                      <li><i class="far fa-circle text-info"></i> Matina</li>
                      <li><i class="far fa-circle text-primary"></i> Sta. Cruz</li>
                      <li><i class="far fa-circle text-secondary"></i> Toril</li>
                    </ul>
                  </div>
  
                </div>

              </div>

              <div class="card-footer bg-light p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      Agdao
                      <span class="float-right text-danger">
                        <i class="fas fa-arrow-down text-sm"></i>
                        1%</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      Sta. Cruz
                      <span class="float-right text-success">
                        <i class="fas fa-arrow-up text-sm"></i> 4%
                      </span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      Tibungco
                      <span class="float-right text-warning">
                        <i class="fas fa-arrow-left text-sm"></i> 0%
                      </span>
                    </a>
                  </li>
                </ul>
              </div>
              <!-- /.footer -->
            </div>


            <!-- PRODUCT LIST -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Recently Added Program</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>

              <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                  <li class="item">
                    <div class="product-img">
                      <img src="{{asset('admin_lte/dist/img/default-150x150.png')}}" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">D-999
                        <span class="badge badge-warning float-right">₱1800</span></a>
                      <span class="product-description">
                        10 year Retirement Plan
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                  <li class="item">
                    <div class="product-img">
                      <img src="{{asset('admin_lte/dist/img/default-150x150.png')}}" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">D-998
                        <span class="badge badge-info float-right">₱700</span></a>
                      <span class="product-description">
                        Money in-case of Emergency
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                  <li class="item">
                    <div class="product-img">
                      <img src="{{asset('admin_lte/dist/img/default-150x150.png')}}" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">
                        F-101<span class="badge badge-danger float-right">
                          ₱350
                      </span>
                      </a>
                      <span class="product-description">
                        Funeral Services with Caro
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                  <li class="item">
                    <div class="product-img">
                      <img src="{{asset('admin_lte/dist/img/default-150x150.png')}}" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">F-100
                        <span class="badge badge-success float-right">₱399</span></a>
                      <span class="product-description">
                        Funeral Services with Embalming
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                </ul>
              </div>

              <div class="card-footer text-center">
                <a href="#" class="uppercase">View All Programs</a>
              </div>

            </div>

          </div>

        </div>

        <!-- /.row -->
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->