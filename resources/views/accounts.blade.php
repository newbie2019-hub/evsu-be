<table>
 <thead>
 <tr>
  <th>Student Number</th>
  <th>Officially Enrolled</th>
  <th>First Name</th>
  <th>Middle Name</th>
  <th>Last Name</th>
  <th>Ext. Name</th>
  <th>Date of Birth</th>
  <th>Primary Contact Number</th>
  <th>Secondary Contact Number</th>
  <th>Account Status</th>
  <th>Primary Email</th>
  <th>is Verified</th>
  <th>Program</th>
  <th>Year Level</th>
  <th>TES Award</th>
  <th>TES Application Number</th>
  <th>TES Grant Type</th>
  <th>Street</th>
  <th>Barangay</th>
  <th>Town</th>
  <th>Province</th>
  <th>Zipcode</th>
  @foreach ($accounts[0]->schoolinfo as $schoolinfo)
      <th>School Year</th>
      <th>Semester</th>
      <th>GWA</th>
      <th>Units Enrolled</th>
  @endforeach
 </tr>
 </thead>
 <tbody>
 @foreach($accounts as $account)
     <tr>
         <td>{{ $account->student_number }}</td>
         <td>{{ $account->enrollment_status }}</td>
         <td>{{ $account->info->first_name }}</td>
         <td>{{ $account->info->middle_name }}</td>
         <td>{{ $account->info->last_name }}</td>
         <td>{{ $account->info->ext_name }}</td>
         <td>{{ $account->info->birthday }}</td>
         <td>{{ $account->info->contact_number }}</td>
         <td>{{ $account->info->contact_number2 }}</td>
         <td>{{ $account->status }}</td>
         <td>{{ $account->email }}</td>
         <td>{{ $account->email_verified_at ? 'Verified' : 'Pending' }}</td>
         <td>{{ $account->info->program }}</td>
         <td>{{ $account->info->year_level }}</td>
         <td>{{ $account->info->tes_award }}</td>
         <td>{{ $account->info->tes_application_number }}</td>
         <td>{{ $account->info->tes_grant_type }}</td>
         <td>{{ $account->info->street }}</td>
         <td>{{ $account->info->barangay }}</td>
         <td>{{ $account->info->town }}</td>
         <td>{{ $account->info->province }}</td>
         <td>{{ $account->info->zipcode }}</td>
         @foreach ($account->schoolinfo as $schoolinfo)
            <td>{{ $schoolinfo->school_year }}</td>
            <td>{{ $schoolinfo->semester }}</td>
            <td>{{ $schoolinfo->gwa }}</td>
            <td>{{ $schoolinfo->units }}</td>
         @endforeach
     </tr>
 @endforeach
 </tbody>
</table>