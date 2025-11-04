import { test, expect } from '@playwright/test';
import { execSync } from 'child_process';

test.describe('Pengajuan Magang', () => {
    test.beforeAll(async () => {
        console.log('🔁 Reset DB untuk suite ini...');
        execSync('php artisan migrate:fresh --seed', { stdio: 'inherit' });
    });

    test('Mahasiswa mengajukan magang', async ({ page }) => {
        await page.goto('http://127.0.0.1:8000/');

        await page.getByRole('link', { name: 'Masuk' }).nth(1).click();
        await page.getByRole('textbox', { name: 'Username' }).click();
        await page.getByRole('textbox', { name: 'Username' }).fill('mahasiswa1');
        await page.getByRole('textbox', { name: 'Password' }).click();
        await page.getByRole('textbox', { name: 'Password' }).fill('mahasiswa123');
        await page.getByRole('button', { name: 'Masuk' }).click();
        await page.getByRole('button', { name: 'Detail' }).first().click();
        await page.getByRole('button', { name: 'Ajukan' }).click();
        await page.getByRole('button', { name: 'OK' }).click();
    });
});
