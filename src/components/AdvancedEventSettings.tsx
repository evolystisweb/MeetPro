import { Card, CardContent, CardDescription, CardHeader, CardTitle } from './ui/card';
import { Label } from './ui/label';
import { Input } from './ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from './ui/select';
import { Switch } from './ui/switch';
import { Settings, Clock, Calendar, Users } from 'lucide-react';

interface AdvancedEventSettingsProps {
  settings: {
    buffer_before: number;
    buffer_after: number;
    min_notice: number;
    max_advance_booking: number;
    max_bookings_per_day: number;
    allow_rescheduling: boolean;
    reschedule_notice: number;
    allow_cancellation: boolean;
    cancellation_notice: number;
  };
  onChange: (settings: any) => void;
}

export function AdvancedEventSettings({ settings, onChange }: AdvancedEventSettingsProps) {
  const updateSetting = (key: string, value: any) => {
    onChange({ ...settings, [key]: value });
  };

  return (
    <Card>
      <CardHeader>
        <CardTitle className="flex items-center">
          <Settings className="h-5 w-5 mr-2" />
          Paramètres Avancés
        </CardTitle>
        <CardDescription>
          Configurez les règles de planification et de gestion des réservations
        </CardDescription>
      </CardHeader>
      <CardContent className="space-y-6">
        <div className="space-y-4">
          <div className="flex items-center space-x-2">
            <Clock className="h-4 w-4 text-muted-foreground" />
            <h3 className="text-sm font-semibold">Tampons temporels</h3>
          </div>

          <div className="grid grid-cols-2 gap-4 pl-6">
            <div className="space-y-2">
              <Label htmlFor="buffer_before">Temps avant (minutes)</Label>
              <Input
                id="buffer_before"
                type="number"
                min="0"
                value={settings.buffer_before}
                onChange={(e) => updateSetting('buffer_before', parseInt(e.target.value))}
                placeholder="Ex: 15"
              />
              <p className="text-xs text-muted-foreground">
                Temps libre avant chaque rendez-vous
              </p>
            </div>

            <div className="space-y-2">
              <Label htmlFor="buffer_after">Temps après (minutes)</Label>
              <Input
                id="buffer_after"
                type="number"
                min="0"
                value={settings.buffer_after}
                onChange={(e) => updateSetting('buffer_after', parseInt(e.target.value))}
                placeholder="Ex: 15"
              />
              <p className="text-xs text-muted-foreground">
                Temps libre après chaque rendez-vous
              </p>
            </div>
          </div>
        </div>

        <div className="space-y-4">
          <div className="flex items-center space-x-2">
            <Calendar className="h-4 w-4 text-muted-foreground" />
            <h3 className="text-sm font-semibold">Délais de réservation</h3>
          </div>

          <div className="grid grid-cols-2 gap-4 pl-6">
            <div className="space-y-2">
              <Label htmlFor="min_notice">Préavis minimum (heures)</Label>
              <Input
                id="min_notice"
                type="number"
                min="0"
                value={settings.min_notice}
                onChange={(e) => updateSetting('min_notice', parseInt(e.target.value))}
                placeholder="Ex: 24"
              />
              <p className="text-xs text-muted-foreground">
                Délai minimum pour réserver
              </p>
            </div>

            <div className="space-y-2">
              <Label htmlFor="max_advance">Réservation anticipée max (jours)</Label>
              <Input
                id="max_advance"
                type="number"
                min="1"
                value={settings.max_advance_booking}
                onChange={(e) => updateSetting('max_advance_booking', parseInt(e.target.value))}
                placeholder="Ex: 60"
              />
              <p className="text-xs text-muted-foreground">
                Limite de réservation à l'avance
              </p>
            </div>
          </div>
        </div>

        <div className="space-y-4">
          <div className="flex items-center space-x-2">
            <Users className="h-4 w-4 text-muted-foreground" />
            <h3 className="text-sm font-semibold">Limites de réservation</h3>
          </div>

          <div className="space-y-2 pl-6">
            <Label htmlFor="max_per_day">Maximum par jour</Label>
            <Input
              id="max_per_day"
              type="number"
              min="1"
              value={settings.max_bookings_per_day}
              onChange={(e) => updateSetting('max_bookings_per_day', parseInt(e.target.value))}
              placeholder="Ex: 10"
            />
            <p className="text-xs text-muted-foreground">
              Nombre maximum de réservations acceptées par jour
            </p>
          </div>
        </div>

        <div className="space-y-4 border-t pt-4">
          <h3 className="text-sm font-semibold">Politique de reprogrammation</h3>

          <div className="space-y-4 pl-6">
            <div className="flex items-center justify-between">
              <div className="space-y-0.5">
                <Label htmlFor="allow_reschedule">Autoriser la reprogrammation</Label>
                <p className="text-xs text-muted-foreground">
                  Les clients peuvent modifier leur rendez-vous
                </p>
              </div>
              <Switch
                id="allow_reschedule"
                checked={settings.allow_rescheduling}
                onCheckedChange={(checked) => updateSetting('allow_rescheduling', checked)}
              />
            </div>

            {settings.allow_rescheduling && (
              <div className="space-y-2">
                <Label htmlFor="reschedule_notice">Préavis de reprogrammation (heures)</Label>
                <Input
                  id="reschedule_notice"
                  type="number"
                  min="0"
                  value={settings.reschedule_notice}
                  onChange={(e) => updateSetting('reschedule_notice', parseInt(e.target.value))}
                  placeholder="Ex: 24"
                />
              </div>
            )}
          </div>
        </div>

        <div className="space-y-4 border-t pt-4">
          <h3 className="text-sm font-semibold">Politique d'annulation</h3>

          <div className="space-y-4 pl-6">
            <div className="flex items-center justify-between">
              <div className="space-y-0.5">
                <Label htmlFor="allow_cancel">Autoriser l'annulation</Label>
                <p className="text-xs text-muted-foreground">
                  Les clients peuvent annuler leur rendez-vous
                </p>
              </div>
              <Switch
                id="allow_cancel"
                checked={settings.allow_cancellation}
                onCheckedChange={(checked) => updateSetting('allow_cancellation', checked)}
              />
            </div>

            {settings.allow_cancellation && (
              <div className="space-y-2">
                <Label htmlFor="cancel_notice">Préavis d'annulation (heures)</Label>
                <Input
                  id="cancel_notice"
                  type="number"
                  min="0"
                  value={settings.cancellation_notice}
                  onChange={(e) => updateSetting('cancellation_notice', parseInt(e.target.value))}
                  placeholder="Ex: 24"
                />
              </div>
            )}
          </div>
        </div>
      </CardContent>
    </Card>
  );
}
