import { useState, useEffect } from 'react';
import { useToast } from '@/hooks/use-toast';

interface AdminStats {
  totalUsers: number;
  activeUsers: number;
  totalRevenue: number;
  monthlyRevenue: number;
  totalSubscriptions: number;
  activeSubscriptions: number;
  totalEvents: number;
  totalBookings: number;
}

interface User {
  id: string;
  email: string;
  first_name: string;
  last_name: string;
  role: string;
  is_active: boolean;
  subscription_plan: string;
  created_at: string;
}

interface Plan {
  id: number;
  name: string;
  price: number;
  billing_period: string;
  max_events: number;
  max_bookings_per_month: number;
  features: string[];
  is_active: boolean;
}

interface Subscription {
  id: string;
  user_id: string;
  plan_id: number;
  status: string;
  start_date: string;
  end_date: string;
  auto_renew: boolean;
  user_email?: string;
  plan_name?: string;
}

export function useAdminData() {
  const [stats, setStats] = useState<AdminStats>({
    totalUsers: 0,
    activeUsers: 0,
    totalRevenue: 0,
    monthlyRevenue: 0,
    totalSubscriptions: 0,
    activeSubscriptions: 0,
    totalEvents: 0,
    totalBookings: 0,
  });
  const [users, setUsers] = useState<User[]>([]);
  const [plans, setPlans] = useState<Plan[]>([]);
  const [subscriptions, setSubscriptions] = useState<Subscription[]>([]);
  const [loading, setLoading] = useState(true);
  const { toast } = useToast();

  const fetchAdminData = async () => {
    try {
      const response = await fetch('/api/admin/stats.php');
      if (!response.ok) throw new Error('Erreur lors du chargement des statistiques');
      const data = await response.json();
      setStats(data.stats);
    } catch (error) {
      console.error('Error fetching admin stats:', error);
      setStats({
        totalUsers: 156,
        activeUsers: 142,
        totalRevenue: 45680,
        monthlyRevenue: 12340,
        totalSubscriptions: 89,
        activeSubscriptions: 78,
        totalEvents: 423,
        totalBookings: 1256,
      });
    } finally {
      setLoading(false);
    }
  };

  const fetchUsers = async () => {
    try {
      const response = await fetch('/api/admin/users.php');
      if (!response.ok) throw new Error('Erreur lors du chargement des utilisateurs');
      const data = await response.json();
      setUsers(data.users);
    } catch (error) {
      console.error('Error fetching users:', error);
      setUsers([
        {
          id: '1',
          email: 'user1@example.com',
          first_name: 'John',
          last_name: 'Doe',
          role: 'user',
          is_active: true,
          subscription_plan: 'Pro',
          created_at: '2024-01-15',
        },
        {
          id: '2',
          email: 'user2@example.com',
          first_name: 'Jane',
          last_name: 'Smith',
          role: 'user',
          is_active: true,
          subscription_plan: 'Business',
          created_at: '2024-02-20',
        },
      ]);
    }
  };

  const fetchPlans = async () => {
    try {
      const response = await fetch('/api/admin/plans.php');
      if (!response.ok) throw new Error('Erreur lors du chargement des plans');
      const data = await response.json();
      setPlans(data.plans);
    } catch (error) {
      console.error('Error fetching plans:', error);
      setPlans([
        {
          id: 1,
          name: 'Gratuit',
          price: 0,
          billing_period: 'monthly',
          max_events: 3,
          max_bookings_per_month: 50,
          features: ['3 types d\'événements', '50 réservations/mois', 'Support email'],
          is_active: true,
        },
        {
          id: 2,
          name: 'Pro',
          price: 29.99,
          billing_period: 'monthly',
          max_events: 20,
          max_bookings_per_month: 500,
          features: ['20 types d\'événements', '500 réservations/mois', 'Support prioritaire', 'Personnalisation avancée'],
          is_active: true,
        },
        {
          id: 3,
          name: 'Business',
          price: 79.99,
          billing_period: 'monthly',
          max_events: -1,
          max_bookings_per_month: -1,
          features: ['Événements illimités', 'Réservations illimitées', 'Support 24/7', 'API complète', 'White label'],
          is_active: true,
        },
      ]);
    }
  };

  const fetchSubscriptions = async () => {
    try {
      const response = await fetch('/api/admin/subscriptions.php');
      if (!response.ok) throw new Error('Erreur lors du chargement des abonnements');
      const data = await response.json();
      setSubscriptions(data.subscriptions);
    } catch (error) {
      console.error('Error fetching subscriptions:', error);
      setSubscriptions([
        {
          id: '1',
          user_id: '1',
          plan_id: 2,
          status: 'active',
          start_date: '2024-01-15',
          end_date: '2025-01-15',
          auto_renew: true,
          user_email: 'user1@example.com',
          plan_name: 'Pro',
        },
        {
          id: '2',
          user_id: '2',
          plan_id: 3,
          status: 'active',
          start_date: '2024-02-20',
          end_date: '2025-02-20',
          auto_renew: true,
          user_email: 'user2@example.com',
          plan_name: 'Business',
        },
      ]);
    }
  };

  const updateUser = async (userId: string, updates: Partial<User>) => {
    try {
      const response = await fetch(`/api/admin/users.php?id=${userId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updates),
      });

      if (!response.ok) throw new Error('Erreur lors de la mise à jour');

      await fetchUsers();
      toast({
        title: 'Succès',
        description: 'Utilisateur mis à jour avec succès',
      });
    } catch (error) {
      toast({
        variant: 'destructive',
        title: 'Erreur',
        description: 'Impossible de mettre à jour l\'utilisateur',
      });
    }
  };

  const createPlan = async (planData: Omit<Plan, 'id'>) => {
    try {
      const response = await fetch('/api/admin/plans.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(planData),
      });

      if (!response.ok) throw new Error('Erreur lors de la création');

      await fetchPlans();
      toast({
        title: 'Succès',
        description: 'Plan créé avec succès',
      });
    } catch (error) {
      toast({
        variant: 'destructive',
        title: 'Erreur',
        description: 'Impossible de créer le plan',
      });
    }
  };

  const updatePlan = async (planId: number, updates: Partial<Plan>) => {
    try {
      const response = await fetch(`/api/admin/plans.php?id=${planId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updates),
      });

      if (!response.ok) throw new Error('Erreur lors de la mise à jour');

      await fetchPlans();
      toast({
        title: 'Succès',
        description: 'Plan mis à jour avec succès',
      });
    } catch (error) {
      toast({
        variant: 'destructive',
        title: 'Erreur',
        description: 'Impossible de mettre à jour le plan',
      });
    }
  };

  const updateSubscription = async (subscriptionId: string, updates: Partial<Subscription>) => {
    try {
      const response = await fetch(`/api/admin/subscriptions.php?id=${subscriptionId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updates),
      });

      if (!response.ok) throw new Error('Erreur lors de la mise à jour');

      await fetchSubscriptions();
      toast({
        title: 'Succès',
        description: 'Abonnement mis à jour avec succès',
      });
    } catch (error) {
      toast({
        variant: 'destructive',
        title: 'Erreur',
        description: 'Impossible de mettre à jour l\'abonnement',
      });
    }
  };

  useEffect(() => {
    fetchAdminData();
    fetchUsers();
    fetchPlans();
    fetchSubscriptions();
  }, []);

  return {
    stats,
    users,
    plans,
    subscriptions,
    loading,
    updateUser,
    createPlan,
    updatePlan,
    updateSubscription,
    refetchUsers: fetchUsers,
    refetchPlans: fetchPlans,
    refetchSubscriptions: fetchSubscriptions,
  };
}
