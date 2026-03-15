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

    <div class="filter-tab">
        <select id="status" name="status">
            <option value="all">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>
    
    <div class="table-container">
        <table>
        <tr>
            <th>Application ID | </th>
            <th>Student ID | </th>
            <th>Scholarship | </th>
            <th>Current Status | </th>
            <th>Submitted on | </th>
            <th>Reviewed by</th>
        </tr>
        
        </table>
    </div>

    <br>

    <div class="go-back">
        <button onclick="window.location.href='#'">Go Back</button>
    </div>
</body>
</html>