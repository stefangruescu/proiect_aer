<?php
$selected_city = $_GET['city'] ?? null;
$data = get_filtered_measurements($pdo, $selected_city);

$eco_tips = [
    "Redu poluarea: Mergi cu bicicleta sau folosește transportul public astăzi!",
    "Protejează-ți plămânii: Verifică indicele PM10 înainte de a face sport în aer liber.",
    "Economisește energie: O casă eficientă termic înseamnă mai puține emisii de la centrale."
];
$daily_tip = $eco_tips[array_rand($eco_tips)];
?>

<main>
    <aside
        style="background: rgba(23, 162, 184, 0.1); border-left: 4px solid #17a2b8; padding: 15px; margin-bottom: 20px; border-radius: 0 5px 5px 0;">
        <p style="color: #17a2b8; font-weight: bold; margin: 0 0 5px 0;">💡 Sfat Ecologic al Zilei:</p>
        <p style="color: #eee; margin: 0; font-style: italic;"><?= $daily_tip ?></p>
    </aside>

    <section class="filter-section"
        style="background: #1a1a1a; padding: 20px; border-radius: 8px; border: 1px solid #333; margin-bottom: 30px;">
        <form method="GET" action="index.php"
            style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 20px; justify-content: space-between;">
            <input type="hidden" name="page" value="dashboard">
            <div style="display: flex; align-items: flex-end; gap: 15px;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label for="city-select"
                        style="color: #888; font-size: 0.85rem; font-weight: bold; text-transform: uppercase;">Oraș</label>
                    <select name="city" id="city-select"
                        style="background: #000; color: white; border: 1px solid #444; padding: 10px 15px; border-radius: 5px; min-width: 200px;">
                        <option value="">Toate orașele</option>
                        <?php
                        $cities = ["Bucuresti", "Cluj-Napoca", "Iasi", "Timisoara", "Craiova"];
                        foreach ($cities as $c): ?>
                            <option value="<?= $c ?>" <?= $selected_city == $c ? 'selected' : '' ?>><?= $c ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit"
                    style="background: #28a745; color: white; border: none; padding: 11px 20px; border-radius: 5px; font-weight: bold; cursor: pointer; height: 42px;">Aplică
                    Filtru</button>
                <?php if ($selected_city): ?>
                    <a href="index.php?page=dashboard" style="color: #ff4d4d; font-size: 0.8rem; text-decoration: none;">✖
                        Resetează</a>
                <?php endif; ?>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="index.php?page=export&city=<?= e($selected_city) ?>" class="btn-export"
                    style="background: #333; color: white; text-decoration: none; padding: 11px 18px; border-radius: 5px; font-weight: bold; font-size: 0.9rem; border: 1px solid #444;"><span>📥</span>
                    CSV</a>
                <button type="button" onclick="window.print()" class="btn-pdf"
                    style="background: #dc3545; color: white; border: none; padding: 11px 18px; border-radius: 5px; font-weight: bold; font-size: 0.9rem; cursor: pointer;"><span>📄</span>
                    PDF Report</button>
            </div>
        </form>
    </section>

    <section style="margin: 25px 0;">
        <a href="index.php?page=add" style="text-decoration: none;">
            <button
                style="background-color: #28a745; color: white; border: none; padding: 10px 18px; border-radius: 5px; font-weight: bold; cursor: pointer;">+
                Adaugă Măsurătoare Nouă</button>
        </a>
    </section>

    <table>
        <thead>
            <tr>
                <th>Oraș</th>
                <th><img src="https://cdn-icons-png.flaticon.com/512/1684/1684425.png" alt=""
                        style="width: 16px; filter: invert(1); vertical-align: middle; margin-right: 5px;"> PM10</th>
                <th>Status Calitate</th>
                <th><img src="https://cdn-icons-png.flaticon.com/512/2948/2948154.png" alt=""
                        style="width: 16px; filter: invert(1); vertical-align: middle; margin-right: 5px;"> Data</th>
                <?php if (is_admin()): ?>
                    <th style="text-align: center;">Acțiuni</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if (empty($data)) {
                echo "<tr><td colspan='5' style='text-align:center; padding: 20px; color: #888;'>Nu există date pentru selecția curentă.</td></tr>";
            } else {
                foreach ($data as $row) {
                    echo render_measurement_row($row, is_admin());
                }
            }
            ?>
        </tbody>
    </table>
</main>