<?php
// utils/Validator.php - Clase para validaciones

class Validator {
    
    public static function required($value) {
        return !empty(trim($value));
    }
    
    public static function email($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function minLength($value, $min) {
        return strlen(trim($value)) >= $min;
    }
    
    public static function maxLength($value, $max) {
        return strlen(trim($value)) <= $max;
    }
    
    public static function numeric($value) {
        return is_numeric($value);
    }
    
    public static function integer($value) {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
    
    public static function positiveNumber($value) {
        return is_numeric($value) && $value > 0;
    }
    
    public static function date($value, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $value);
        return $d && $d->format($format) === $value;
    }
    
    public static function time($value, $format = 'H:i') {
        $d = DateTime::createFromFormat($format, $value);
        return $d && $d->format($format) === $value;
    }
    
    public static function inArray($value, $array) {
        return in_array($value, $array);
    }
    
    public static function unique($value, $table, $column, $except_id = null) {
        $db = Database::getInstance();
        
        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
        $params = [$value];
        
        if ($except_id) {
            $sql .= " AND id != ?";
            $params[] = $except_id;
        }
        
        $result = $db->queryOne($sql, $params);
        return $result['count'] == 0;
    }
    
    public static function validateForm($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? '';
            
            foreach ($fieldRules as $rule => $ruleValue) {
                switch ($rule) {
                    case 'required':
                        if ($ruleValue && !self::required($value)) {
                            $errors[$field][] = "El campo {$field} es requerido";
                        }
                        break;
                        
                    case 'email':
                        if ($ruleValue && !empty($value) && !self::email($value)) {
                            $errors[$field][] = "El campo {$field} debe ser un email válido";
                        }
                        break;
                        
                    case 'min_length':
                        if (!empty($value) && !self::minLength($value, $ruleValue)) {
                            $errors[$field][] = "El campo {$field} debe tener al menos {$ruleValue} caracteres";
                        }
                        break;
                        
                    case 'max_length':
                        if (!empty($value) && !self::maxLength($value, $ruleValue)) {
                            $errors[$field][] = "El campo {$field} no puede tener más de {$ruleValue} caracteres";
                        }
                        break;
                        
                    case 'numeric':
                        if ($ruleValue && !empty($value) && !self::numeric($value)) {
                            $errors[$field][] = "El campo {$field} debe ser numérico";
                        }
                        break;
                        
                    case 'integer':
                        if ($ruleValue && !empty($value) && !self::integer($value)) {
                            $errors[$field][] = "El campo {$field} debe ser un número entero";
                        }
                        break;
                        
                    case 'positive':
                        if ($ruleValue && !empty($value) && !self::positiveNumber($value)) {
                            $errors[$field][] = "El campo {$field} debe ser un número positivo";
                        }
                        break;
                        
                    case 'date':
                        if ($ruleValue && !empty($value) && !self::date($value)) {
                            $errors[$field][] = "El campo {$field} debe ser una fecha válida (YYYY-MM-DD)";
                        }
                        break;
                        
                    case 'time':
                        if ($ruleValue && !empty($value) && !self::time($value)) {
                            $errors[$field][] = "El campo {$field} debe ser una hora válida (HH:MM)";
                        }
                        break;
                        
                    case 'in':
                        if (is_array($ruleValue) && !empty($value) && !self::inArray($value, $ruleValue)) {
                            $errors[$field][] = "El valor de {$field} no es válido";
                        }
                        break;
                        
                    case 'unique':
                        if (is_array($ruleValue) && !empty($value)) {
                            $table = $ruleValue['table'];
                            $column = $ruleValue['column'] ?? $field;
                            $except_id = $ruleValue['except'] ?? null;
                            
                            if (!self::unique($value, $table, $column, $except_id)) {
                                $errors[$field][] = "El valor de {$field} ya existe en el sistema";
                            }
                        }
                        break;
                }
            }
        }
        
        // Convertir arrays de errores en strings
        foreach ($errors as $field => $fieldErrors) {
            $errors[$field] = implode(', ', $fieldErrors);
        }
        
        return $errors;
    }
    
    public static function sanitize($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        } else {
            return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
        }
    }
    
    public static function cleanNumeric($value) {
        return preg_replace('/[^0-9.-]/', '', $value);
    }
    
    public static function cleanPhone($value) {
        return preg_replace('/[^0-9+\-\s\(\)]/', '', $value);
    }
}

?>