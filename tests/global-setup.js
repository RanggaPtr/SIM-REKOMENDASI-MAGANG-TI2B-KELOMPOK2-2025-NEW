import { execSync } from 'child_process';

export default async () => {
    console.log('🔁 Resetting and seeding Laravel database...');
    execSync('php artisan migrate:fresh --seed', { stdio: 'inherit' });
    console.log('✅ Database refreshed.');
};
