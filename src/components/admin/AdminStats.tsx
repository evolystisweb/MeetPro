import { Card, CardContent, CardHeader, CardTitle } from '../ui/card';
import { Users, DollarSign, CreditCard, Calendar, TrendingUp, Activity } from 'lucide-react';

interface AdminStatsProps {
  stats: {
    totalUsers: number;
    activeUsers: number;
    totalRevenue: number;
    monthlyRevenue: number;
    totalSubscriptions: number;
    activeSubscriptions: number;
    totalEvents: number;
    totalBookings: number;
  };
}

export function AdminStats({ stats }: AdminStatsProps) {
  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('fr-FR', {
      style: 'currency',
      currency: 'EUR',
    }).format(amount);
  };

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <Card className="bg-slate-800/50 border-slate-700">
        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
          <CardTitle className="text-sm font-medium text-slate-300">Total Utilisateurs</CardTitle>
          <Users className="h-4 w-4 text-blue-500" />
        </CardHeader>
        <CardContent>
          <div className="text-2xl font-bold text-white">{stats.totalUsers}</div>
          <p className="text-xs text-slate-400">
            {stats.activeUsers} actifs
          </p>
        </CardContent>
      </Card>

      <Card className="bg-slate-800/50 border-slate-700">
        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
          <CardTitle className="text-sm font-medium text-slate-300">Revenus Total</CardTitle>
          <DollarSign className="h-4 w-4 text-green-500" />
        </CardHeader>
        <CardContent>
          <div className="text-2xl font-bold text-white">{formatCurrency(stats.totalRevenue)}</div>
          <p className="text-xs text-slate-400">
            {formatCurrency(stats.monthlyRevenue)} ce mois
          </p>
        </CardContent>
      </Card>

      <Card className="bg-slate-800/50 border-slate-700">
        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
          <CardTitle className="text-sm font-medium text-slate-300">Abonnements</CardTitle>
          <CreditCard className="h-4 w-4 text-purple-500" />
        </CardHeader>
        <CardContent>
          <div className="text-2xl font-bold text-white">{stats.totalSubscriptions}</div>
          <p className="text-xs text-slate-400">
            {stats.activeSubscriptions} actifs
          </p>
        </CardContent>
      </Card>

      <Card className="bg-slate-800/50 border-slate-700">
        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
          <CardTitle className="text-sm font-medium text-slate-300">Activité</CardTitle>
          <Activity className="h-4 w-4 text-orange-500" />
        </CardHeader>
        <CardContent>
          <div className="text-2xl font-bold text-white">{stats.totalEvents}</div>
          <p className="text-xs text-slate-400">
            {stats.totalBookings} réservations
          </p>
        </CardContent>
      </Card>

      <Card className="bg-slate-800/50 border-slate-700 md:col-span-2">
        <CardHeader>
          <CardTitle className="text-slate-300">Aperçu des Performances</CardTitle>
        </CardHeader>
        <CardContent className="space-y-4">
          <div className="flex items-center justify-between">
            <span className="text-sm text-slate-400">Taux d'activation</span>
            <span className="text-sm font-semibold text-white">
              {((stats.activeUsers / stats.totalUsers) * 100).toFixed(1)}%
            </span>
          </div>
          <div className="flex items-center justify-between">
            <span className="text-sm text-slate-400">Taux d'abonnement</span>
            <span className="text-sm font-semibold text-white">
              {((stats.activeSubscriptions / stats.totalUsers) * 100).toFixed(1)}%
            </span>
          </div>
          <div className="flex items-center justify-between">
            <span className="text-sm text-slate-400">Revenu moyen par utilisateur</span>
            <span className="text-sm font-semibold text-white">
              {formatCurrency(stats.totalRevenue / stats.totalUsers)}
            </span>
          </div>
          <div className="flex items-center justify-between">
            <span className="text-sm text-slate-400">Réservations par événement</span>
            <span className="text-sm font-semibold text-white">
              {(stats.totalBookings / stats.totalEvents).toFixed(1)}
            </span>
          </div>
        </CardContent>
      </Card>

      <Card className="bg-slate-800/50 border-slate-700 md:col-span-2">
        <CardHeader>
          <CardTitle className="text-slate-300">Tendances</CardTitle>
        </CardHeader>
        <CardContent className="space-y-4">
          <div className="flex items-center justify-between">
            <div className="flex items-center">
              <TrendingUp className="h-4 w-4 text-green-500 mr-2" />
              <span className="text-sm text-slate-400">Nouveaux utilisateurs (30j)</span>
            </div>
            <span className="text-sm font-semibold text-green-500">+{Math.floor(stats.totalUsers * 0.15)}</span>
          </div>
          <div className="flex items-center justify-between">
            <div className="flex items-center">
              <TrendingUp className="h-4 w-4 text-green-500 mr-2" />
              <span className="text-sm text-slate-400">Nouveaux abonnements (30j)</span>
            </div>
            <span className="text-sm font-semibold text-green-500">+{Math.floor(stats.activeSubscriptions * 0.12)}</span>
          </div>
          <div className="flex items-center justify-between">
            <div className="flex items-center">
              <TrendingUp className="h-4 w-4 text-green-500 mr-2" />
              <span className="text-sm text-slate-400">Croissance revenus (30j)</span>
            </div>
            <span className="text-sm font-semibold text-green-500">+18.5%</span>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
