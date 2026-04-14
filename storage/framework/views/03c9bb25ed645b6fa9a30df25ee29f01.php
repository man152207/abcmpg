

<?php $__env->startSection('content'); ?>
<style>
  /* केवल khaja section लाई style गर्ने */
  #khaja-print-area {
    font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, "Helvetica Neue", Arial, sans-serif;
    background-color: #fafafa;
    padding: 20px;
    font-size: 16px; /* Screen मा हेर्दा राम्रो देखिने */
  }

  #khaja-print-area .top-bar {
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  #khaja-print-area #print-btn {
    padding: 8px 16px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    background-color: #2563eb;
    color: #ffffff;
  }

  #khaja-print-area #print-hint {
    font-size: 13px;
    color: #4b5563;
  }

  #khaja-print-area table {
    width: 100%;
    border-collapse: collapse;
    background-color: #ffffff;
    font-size: 15px;
    line-height: 1.5;
  }

  #khaja-print-area thead th {
    background-color: #f2f4f7;
    font-weight: 600;
    text-align: left;
    border-bottom: 2px solid #d0d4dd;
    padding: 10px 12px;
  }

  #khaja-print-area tbody td {
    border: 1px solid #e0e4ec;
    padding: 10px 12px;
    vertical-align: top;
  }

  #khaja-print-area tbody tr:nth-child(even) {
    background-color: #f9fafb;
  }

  #khaja-print-area td:first-child {
    font-weight: 600;
    white-space: nowrap;
  }

  #khaja-print-area td:nth-child(2) {
    font-weight: 500;
  }

  #khaja-print-area td,
  #khaja-print-area th {
    cursor: default;
  }

  #khaja-print-area td.editing,
  #khaja-print-area th.editing {
    outline: 2px dashed #2563eb;
    background-color: #eef2ff;
  }

  /* PRINT STYLES */
  @media print {

    /* अरु सब admin layout लुकाउने */
    body * {
      visibility: hidden;
    }

    /* केवल khaja section मात्र देखाउने */
    #khaja-print-area,
    #khaja-print-area * {
      visibility: visible;
    }

    /* full page width लिन */
    #khaja-print-area {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      padding: 10mm;
      background-color: #ffffff;
      font-size: 14px; /* print मा अलि adjust, landscape/portrait दुबैमा fit हुन्छ */
    }

    #khaja-print-area table {
      font-size: 14px;
    }

    #khaja-print-area .top-bar {
      display: none; /* Print गर्दा button नदेखियोस् */
    }

    @page {
      size: auto;   /* user ले portrait/landscape जे छान्यो, त्यसैअनुसार */
      margin: 10mm; /* default page margin */
    }
  }
</style>

<div id="khaja-print-area">
  <div class="top-bar">
    <button id="print-btn">🖨 Print / प्रिन्ट</button>
    <div id="print-hint">
      👉 Table भित्रको text <strong>double click</strong> गरेर edit गर्न सकिन्छ।<br>
      तयार भएपछि <strong>Print / प्रिन्ट</strong> बटन थिचेर A4 portrait वा landscape जे चाहियो, त्यही orientation छानेर प्रिन्ट गर्नुहोस्।
    </div>
  </div>

  <table id="khaja-table">
    <thead>
      <tr>
        <th>Day</th>
        <th>Main</th>
        <th>Details (Mix / Ingredients)</th>
        <th>Why</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Day 1</td>
        <td>Roti + Seasonal Veg Tarkari (Alu mix)</td>
        <td>
          Tarkari mix:<br>
          • ५०% seasonal green sabji (saag, cauliflower, beans, cabbage जे पाइन्छ)<br>
          • ३०% आलु (taste को लागि)<br>
          • २०% carrot / peas जस्तो color दिने veg
        </td>
        <td>हप्ता सुरू – हल्का, veg-heavy, everyone-friendly।</td>
      </tr>
      <tr>
        <td>Day 2</td>
        <td>Toast + Omelet + हल्का Veg</td>
        <td>
          Omelet mix: Onion + capsicum + थोरै boiled potato + coriander<br>
          Side: काक्रो / गाजर / काउलीको सानो salad bowl
        </td>
        <td>ब्रेड base, egg बाट protein, सब्जी पनि छ – energy मिल्ने तर heavy नपर्ने।</td>
      </tr>
      <tr>
        <td>Day 3</td>
        <td>Fried Rice with Egg (Optional Chicken)</td>
        <td>
          Veg: carrot + peas + beans + spring onion<br>
          Protein: scrambled egg<br>
          Optional (rotation): Week 1 – Egg fried rice, Week 2 – Chicken fried rice
        </td>
        <td>Mid-week energy चाहिने दिन, rice based heavy khaja राम्रो हुन्छ।</td>
      </tr>
      <tr>
        <td>Day 4</td>
        <td>Roti with Aalu &amp; Beans</td>
        <td>
          Tarkari mix: ६०% beans, ४०% आलु<br>
          थोरै tomato + onion gravy, masala हल्का
        </td>
        <td>Rice खाइसकियो, अब फेरि roti day; protein का लागि beans ज्यादा, health-friendly।</td>
      </tr>
      <tr>
        <td>Day 5</td>
        <td>Pasta with Egg</td>
        <td>
          Sauce / mix:<br>
          Onion + garlic + tomato base<br>
          Capsicum + sweet corn / peas<br>
          Boiled egg slice / chopped मिलाएर toss गर्ने
        </td>
        <td>हप्ताको अन्ततिर अलि different taste; Italian-type flavor।</td>
      </tr>
      <tr>
        <td>Day 6</td>
        <td>Noodles Chowmein (Veg + Optional Egg/Chicken)</td>
        <td>
          Mix: cabbage + carrot + capsicum + onion (basic chowmein veg)<br>
          Option: सिर्फ veg version वा egg / chicken chowmein (office preference अनुसार)
        </td>
        <td>Weekend mood जस्तो, chowmein fun khaja; प्रायः सबैलाई मनपर्ने आइटम।</td>
      </tr>
    </tbody>
  </table>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById("khaja-table");
    const printBtn = document.getElementById("print-btn");

    // PRINT BUTTON
    printBtn.addEventListener("click", function () {
      window.print();
    });

    // DOUBLE-CLICK TO EDIT
    table.addEventListener("dblclick", function (e) {
      let cell = e.target;

      if (cell && cell.nodeType === Node.TEXT_NODE) {
        cell = cell.parentElement;
      }

      if (!cell || (cell.tagName !== "TD" && cell.tagName !== "TH")) return;

      makeEditable(cell);
    });

    function makeEditable(cell) {
      if (cell.isContentEditable) return;

      cell.classList.add("editing");
      cell.setAttribute("contenteditable", "true");
      cell.focus();

      const range = document.createRange();
      range.selectNodeContents(cell);
      range.collapse(false);
      const sel = window.getSelection();
      sel.removeAllRanges();
      sel.addRange(range);

      const onBlur = () => {
        cell.removeAttribute("contenteditable");
        cell.classList.remove("editing");
        cell.removeEventListener("blur", onBlur);
        cell.removeEventListener("keydown", onKeyDown);
      };

      const onKeyDown = (event) => {
        if (event.key === "Enter" && !event.shiftKey) {
          event.preventDefault();
          cell.blur();
        }
      };

      cell.addEventListener("blur", onBlur);
      cell.addEventListener("keydown", onKeyDown);
    }
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/khaja.blade.php ENDPATH**/ ?>