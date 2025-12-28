import React, { useState } from 'react';
import { useAuth } from '../hooks/useAuth';
import { useAdminData } from '../hooks/useAdminData';
import { Button } from '../components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../components/ui/tabs';
import { Shield, Users, CreditCard, Settings, LogOut, TrendingUp, DollarSign } from 'lucide-react';
import { UsersManagement } from '../components/admin/UsersManagement';
import { PlansManagement } from '../components/admin/PlansManagement';
import { SubscriptionsManagement } from '../components/admin/SubscriptionsManagement';
import { AdminStats } from '../components/admin/AdminStats';

export default function Admin() {
  const { user, signOut } = useAuth();
  const { stats, loading } = useAdminData();
  const [activeTab, setActiveTab] = useState('stats');

  if (loading) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center">
        <div className="text-center">
          <Shield className="h-12 w-12 text-blue-500 mx-auto mb-4 animate-pulse" />
          <p className="text-slate-300">Chargement du panneau d'administration...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
      <header className="bg-slate-950/50 backdrop-blur-sm shadow-lg border-b border-slate-700 sticky top-0 z-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center">
              <Shield className="h-8 w-8 text-blue-500" />
              <h1 className="ml-3 text-xl font-bold text-white">
                Panneau d'Administration
              </h1>
            </div>
            <div className="flex items-center space-x-4">
              <span className="text-sm text-slate-300">
                Admin: {user?.email}
              </span>
              <Button variant="outline" onClick={signOut} size="sm" className="border-slate-600 text-slate-300 hover:bg-slate-800">
                <LogOut className="h-4 w-4 mr-2" />
                Déconnexion
              </Button>
            </div>
          </div>
        </div>
      </header>

      <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <Tabs value={activeTab} onValueChange={setActiveTab} className="space-y-6">
          <TabsList className="grid w-full grid-cols-4 bg-slate-800/50 border border-slate-700">
            <TabsTrigger value="stats" className="data-[state=active]:bg-slate-700">
              <TrendingUp className="h-4 w-4 mr-2" />
              Statistiques
            </TabsTrigger>
            <TabsTrigger value="users" className="data-[state=active]:bg-slate-700">
              <Users className="h-4 w-4 mr-2" />
              Utilisateurs
            </TabsTrigger>
            <TabsTrigger value="plans" className="data-[state=active]:bg-slate-700">
              <DollarSign className="h-4 w-4 mr-2" />
              Plans Tarifaires
            </TabsTrigger>
            <TabsTrigger value="subscriptions" className="data-[state=active]:bg-slate-700">
              <CreditCard className="h-4 w-4 mr-2" />
              Abonnements
            </TabsTrigger>
          </TabsList>

          <TabsContent value="stats" className="space-y-6">
            <AdminStats stats={stats} />
          </TabsContent>

          <TabsContent value="users" className="space-y-6">
            <UsersManagement />
          </TabsContent>

          <TabsContent value="plans" className="space-y-6">
            <PlansManagement />
          </TabsContent>

          <TabsContent value="subscriptions" className="space-y-6">
            <SubscriptionsManagement />
          </TabsContent>
        </Tabs>
      </main>
    </div>
  );
}
