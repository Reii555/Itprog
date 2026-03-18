<!DOCTYPE html>

<html>
    <meta charset="UTF-8">
    <title>Application Management</title>
    <link rel="stylesheet" href="AppMgmt_Design.css">
<body>
    <div class="header">
      <h3>Centralized Scholarship Portal / Admin Navigation / Application Management</h3>
    </div>

    <h1>Application Management</h1>

  <div class="card-stats">
  <div class="stats-inner">
    <div class="card">
      <h2>Total Applications</h2>
      <p>(insert value)</p>
    </div>

    <div class="card">
      <h2>Pending Reviews</h2>
      <p>(insert value)</p>
    </div>

    <div class="card">
      <h2>Approved</h2>
      <p>(insert value)</p>
    </div>
  </div>
</div>

<br>

<!-- Advanced Filtering -->

<div class="Adv-Filtr">
  <div class="filter-dropdown">
    <button class="Advanced-Filter">Advanced Filtering</button>
    <div class="dropdown-content">
      <div class="filter-group">
        <select name="Application Status">
          <option value="all">All status</option>
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>

      <div class="filter-group">
        <select name="Year Level">
          <option value="all">All Year Levels</option>
          <option value="freshman">Freshman</option>
          <option value="sophomore">Sophomore</option>
          <option value="junior">Junior</option>
          <option value="senior">Senior</option>
        </select>
      </div>

      <div class="filter-group">
        <select name="Scholarship">
          <option value="all">All Scholarships</option>
        </select>
      </div>

      <div class="filter-group">
        <label>Sort by submission date</label>
        <div class="date-sorts">
          <button class="sort-asc">Recent Submissions</button>
          <button class="sort-desc">Oldest Submissions</button>
        </div>
      </div>

      <div class="dropdown-footer">
        <button class="clear-filters">Clear</button>
        <button class="apply-filters">Apply</button>
      </div>
    </div>
  </div>
  
  <button class="Clear-Filter">Clear Filter/s</button>
</div>

<!-- Applications Table -->

    <div class="table-container">
        <table>
        <tr>
            <th>Application ID </th>
            <th>Student ID </th>
            <th>Scholarship </th>
            <th>Current Status </th>
            <th>Submitted on </th>
            <th>Reviewed by </th>
            <th>Year Level </th>
        </tr>
    
        </table>
    </div>
</div>

    <br>

    <div class="go-back">
        <button onclick="window.location.href='#'">Go Back</button>
    </div>
</body>
</html>