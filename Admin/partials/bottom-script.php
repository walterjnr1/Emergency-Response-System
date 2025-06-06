<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const logoImg = document.getElementById('logo-img');

    if (file) {
        // Optional: set the selected image as the logo source
        logoImg.src = URL.createObjectURL(file);
        logoImg.style.display = 'block';
    } else {
        logoImg.style.display = 'none';
    }
});

   
</script>


<script>
    document.getElementById('signatureInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const SignatureImg = document.getElementById('signature-img');

    if (file) {
        // Optional: set the selected image as the logo source
        SignatureImg.src = URL.createObjectURL(file);
        SignatureImg.style.display = 'block';
    } else {
      SignatureImg.style.display = 'none';
    }
});

   
</script>

<?php if(!empty($_SESSION['success'])) {  ?>
<div class="popup popup--icon -success js_success-popup popup--visible">
  <div class="popup__background"></div>
  <div class="popup__content">
    <h3 class="popup__content__title">
      <strong>Success</strong> 
    </h1>
    <p><?php echo $_SESSION['success']; ?></p>
    <p>
      <button class="button button--success" data-for="js_success-popup">Close</button>
    </p>
  </div>
</div>
<?php unset($_SESSION["success"]);  
} ?>
<?php if(!empty($_SESSION['error'])) {  ?>
<div class="popup popup--icon -error js_error-popup popup--visible">
  <div class="popup__background"></div>
  <div class="popup__content">
    <h3 class="popup__content__title">
      <strong>Error</strong> 
    </h1>
    <p><?php echo $_SESSION['error']; ?></p>
    <p>
      <button class="button button--error" data-for="js_error-popup">Close</button>
    </p>
  </div>
</div>
<?php unset($_SESSION["error"]);  } ?>
    <script>
      var addButtonTrigger = function addButtonTrigger(el) {
  el.addEventListener('click', function () {
    var popupEl = document.querySelector('.' + el.dataset.for);
    popupEl.classList.toggle('popup--visible');
  });
};

Array.from(document.querySelectorAll('button[data-for]')).
forEach(addButtonTrigger);
    </script>

<script>
    // Object with regions and their districts
    const regionDistricts = {
      "Greater Accra": ["Accra Metropolitan", "Tema Metropolitan", "Ga West", "Ga East", "La Nkwantanang-Madina"],
      "Ashanti": ["Kumasi Metropolitan", "Asokwa", "Obuasi", "Ejisu", "Kwadaso"],
      "Central": ["Cape Coast", "Mfantsiman", "Assin South", "Agona West", "Gomoa East"],
      "Eastern": ["Koforidua", "Akuapim North", "New Juaben South", "Suhum", "Nsawam-Adoagyiri"],
      "Northern": ["Tamale Metropolitan", "Sagnarigu", "Yendi", "Saboba", "Tolon"],
      "Volta": ["Ho", "Keta", "Hohoe", "Kpando", "North Dayi"],
      "Western": ["Sekondi-Takoradi", "Tarkwa-Nsuaem", "Mpohor", "Wassa Amenfi East", "Ahanta West"],
      "Upper East": ["Bolgatanga", "Bawku", "Navrongo", "Bongo", "Zebilla"],
      "Upper West": ["Wa", "Nadowli", "Jirapa", "Lambussie", "Tumu"],
      "Bono": ["Sunyani", "Berekum", "Dormaa", "Wenchi", "Tain"],
      "Bono East": ["Techiman", "Atebubu-Amantin", "Kintampo North", "Nkoranza South"],
      "Ahafo": ["Goaso", "Asunafo South", "Tano North"],
      "Oti": ["Dambai", "Krachi East", "Jasikan"],
      "North East": ["Nalerigu", "Bunkpurugu", "Chereponi"],
      "Savannah": ["Damongo", "Bole", "Salaga North", "Sawla-Tuna-Kalba", "Central Gonja", "West Gonja"],
      "Western North": ["Sefwi Wiawso", "Bibiani-Anhwiaso-Bekwai", "Bodi"]
    };

    // Populate regions on load
    window.onload = function() {
      const regionSelect = document.getElementById("region");
      for (let region in regionDistricts) {
        let option = document.createElement("option");
        option.value = region;
        option.textContent = region;
        regionSelect.appendChild(option);
      }
    };

    // Populate districts based on selected region
    function populateDistricts() {
      const region = document.getElementById("region").value;
      const districtSelect = document.getElementById("district");

      // Clear previous options
      districtSelect.innerHTML = '<option value="">-- Select District --</option>';

      if (region && regionDistricts[region]) {
        regionDistricts[region].forEach(district => {
          const option = document.createElement("option");
          option.value = district;
          option.textContent = district;
          districtSelect.appendChild(option);
        });
      }
    }
  </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>



<script>
        function validateScores() {
        const testScore = parseFloat(document.getElementById("test_score").value) || 0;
        const examScore = parseFloat(document.getElementById("exam_score").value) || 0;

        if (testScore < 0 || testScore > 30) {
          alert("Test score must be between 0 and 30.");
          document.getElementById("test_score").value = "";
        }

        if (examScore < 0 || examScore > 70) {
          alert("Exam score must be between 0 and 70.");
          document.getElementById("exam_score").value = "";
        }
        }
      </script>



<script>
    document.querySelectorAll('.test-score, .exam-score').forEach(input => {
        input.addEventListener('input', () => {
            const row = input.closest('tr');
            const testScoreInput = row.querySelector('.test-score');
            const examScoreInput = row.querySelector('.exam-score');
            const testScore = Math.min(Math.max(parseFloat(testScoreInput.value) || 0, 0), 30);
            const examScore = Math.min(Math.max(parseFloat(examScoreInput.value) || 0, 0), 70);

            testScoreInput.value = testScore;
            examScoreInput.value = examScore;
            row.querySelector('.total-score').value = testScore + examScore;
        });
    });
</script>