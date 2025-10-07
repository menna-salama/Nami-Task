<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Projects Report</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
  <style>
    main {
      max-width: 1100px;
      margin: auto;
    }
    .filter-bar {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1.5rem;
      margin-top: 2rem;
    }

    select {
      padding: 0.6rem 0.8rem;
      font-size: 1rem;
      width: 500px;
      height: 60px;
      border: 1px solid #ddd;
      border-radius: 4px;
      background: white;
    }
    
    select:focus {
      border-color: #007bff;
      outline: none;
      box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }

   .btn{
    width: 150px;
      height: 42px;
      padding: 0 1.2rem;
      font-size: 1rem;
      font-weight: 500;
      background-color: #0077cc;
      color: white;
    }

    button:hover {
      background-color: #005fa3;
    }

    table {
      font-size: 0.95rem;
    }

    h3 {
      margin-top: 2rem;
    }
  </style>
</head>
<body>
<main class="container">
  <h2>Choose Projects</h2>

  <form method="POST" action="{{ route('report.filter') }}">
    @csrf
    <div class="filter-bar">
      <select name="project_id">
        <option value="all" {{ $all ? 'selected' : '' }}>All Projects</option>
        @foreach($projects as $project)
          <option value="{{ $project->id }}" {{ (!$all && !empty($selected) && (int)($selected[0] ?? 0) === $project->id) ? 'selected' : '' }}>
            {{ $project->name }}
          </option>
        @endforeach
      </select>
      <button class="btn" type="submit">Apply</button>
    </div>
  </form>

  <hr>

  <h3>Employees</h3>
  <table role="grid">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Salary</th>
        <th>Hour Cost</th>
      </tr>
    </thead>
    <tbody>
    @foreach($employees as $e)
      <tr>
        <td>{{ $e->id }}</td>
        <td>{{ $e->name }}</td>
        <td>{{ $e->salary }}</td>
        <td>{{ $e->hour_cost ?? '-' }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>

  <h3>Projects</h3>
  <table role="grid">
    <thead>
      <tr>
        <th>Project ID</th>
        <th>Project Name</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Total Days</th>
        <th>Total Employees</th>
        <th>Total Project Cost</th>
      </tr>
    </thead>
    <tbody>
    @foreach($projectStats as $p)
      <tr>
        <td>{{ $p['id'] }}</td>
        <td>{{ $p['name'] }}</td>
        <td>{{ $p['start_date'] ?? '-' }}</td>
        <td>{{ $p['end_date'] ?? '-' }}</td>
        <td>{{ $p['total_days'] ?? '-' }}</td>
        <td>{{ $p['total_employees'] }}</td>
        <td>{{ $p['total_cost'] }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>

  <h3>Time Logs</h3>
  <table role="grid">
    <thead>
      <tr>
        <th>Date</th>
        <th>Employee</th>
        <th>Project</th>
        <th>Hours</th>
        <th>Module</th>
      </tr>
    </thead>
    <tbody>
    @foreach($timeLogs as $r)
      <tr>
        <td>{{ $r['date'] }}</td>
        <td>{{ $r['employee'] }}</td>
        <td>{{ $r['project'] }}</td>
        <td>{{ $r['hours'] }}</td>
        <td>{{ $r['modul'] }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>

  <h3>Modules</h3>
  <table role="grid">
    <thead>
      <tr>
        <th>Employee</th>
        <th>Module Name</th>
        <th>Project</th>
        <th>Hours</th>
      </tr>
    </thead>
    <tbody>
    @foreach($moduleLogs as $r)
      <tr>
        <td>{{ $r['employee'] }}</td>
        <td>{{ $r['modul'] }}</td>
        <td>{{ $r['project'] }}</td>
        <td>{{ $r['hours'] }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>

  <h3>Logs</h3>
  <table role="grid">
    <thead>
      <tr>
        <th>Employee</th>
        @foreach($pivot['dates'] as $d)
          <th>{{ $d }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
    @foreach($pivot['rows'] as $row)
      <tr>
        <td>{{ $row['employee'] }}</td>
        @foreach($pivot['dates'] as $d)
          <td>{{ $row[$d] }}</td>
        @endforeach
      </tr>
    @endforeach
    </tbody>
  </table>
</main>
</body>
</html>
