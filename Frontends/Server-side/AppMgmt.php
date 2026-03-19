<!DOCTYPE html>

<html>
    <meta charset="UTF-8">
    <title>Application Management</title>
    <link rel="stylesheet" href="AppMgmt_Design.css">
<body>
    <section class="header">
    <img src="../icons/CSP_logo.png" alt="CSP Logo" class="CSP-logo">
    <h3>Centralized Scholarship Portal / Admin Navigation / Application Management</h3>
    </section>

    <h1>Application Management</h1>

  <section class="card-stats">
  <section class="stats-inner">
    <section class="card">
      <h2>Total Applications</h2>
      <p>(insert value)</p>
</section>

    <section class="card">
      <h2>Pending Reviews</h2>
      <p>(insert value)</p>
    </section>

    <section class="card">
      <h2>Approved</h2>
      <p>(insert value)</p>
    </section>
  </section>
</section>

<br>

<!-- Advanced Filtering -->

<section class="Adv-Filtr">
  <section class="filter-dropdown">
    <button class="Advanced-Filter">Advanced Filtering</button>
    <section class="dropdown-content">
      <section class="filter-group">
        <select name="Application Status">
          <option value="all">All status</option>
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
      </section>

      <section class="filter-group">
        <select name="Year Level">
          <option value="all">All Year Levels</option>
          <option value="freshman">Freshman</option>
          <option value="sophomore">Sophomore</option>
          <option value="junior">Junior</option>
          <option value="senior">Senior</option>
        </select>
      </section>

      <section class="filter-group">
        <select name="Scholarship">
          <option value="all">All Scholarships</option>
        </select>
      </section>

      <section class="filter-group">
        <label>Sort by submission date</label>
        <section class="date-sorts">
          <button class="sort-asc">Recent Submissions</button>
          <button class="sort-desc">Oldest Submissions</button>
        </section>
      </section>

      <section class="dropdown-footer">
        <button class="clear-filters">Clear</button>
        <button class="apply-filters">Apply</button>
      </section>
    </section>
  </section>

  <button class="Clear-Filter">Clear Filter/s</button>
</section>

<!-- Applications Table -->

    <section class="table-container">
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
    </section>
</section>

    <br>

    <section class="go-back">
        <button onclick="window.location.href='#'">Go Back</button>
    </section>
</body>
</html>